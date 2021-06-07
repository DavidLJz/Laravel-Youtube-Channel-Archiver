<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\YoutubeService;

use App\Models\channel;
use App\Models\video;
use App\Models\video_metadata_sync_log as log;

class FetchChannelVideosMetadata implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $channel_pk;
    protected $page_token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $channel_pk, string $page_token='')
    {
        $this->channel_pk = $channel_pk;
        $this->page_token = $page_token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $channel = channel::find($this->channel_pk);

        if (empty($channel)) {
            throw new \Exception(
                "Channel with primary key {$this->channel_pk} not found"
            );
        }

        $yt = new YoutubeService;

        $channel_metadata = $yt->getChannel($channel->yt_id, 'id');

        if (empty($channel_metadata)) {
            throw new \Exception(
                "Error fetching channel metadata: {$channel->name}"
            );
        }

        $channel = $this->updateChannelData($channel, $channel_metadata);

        // 20 videos
        $response = $yt->getVideoList($channel->yt_id, $this->page_token);

        if ( empty($response['videos']) ) {
            \Log::debug('No videos returned', [
                'response' => $response,
                'channel' => [ 'pk' => $this->channel_pk, 'name' => $channel->name ]
            ]);

            return;
        }

        foreach ($response['videos'] as $v) {
            video::updateOrInsert([
                'yt_id' => $v['yt_id'],
                'channel_pk' => $this->channel_pk 
            ], [
                'title' => $v['title'],
                'description' => $v['description']
            ]);
        }

        if ( !empty($response['pagination']['nextPageToken']) ) {
            $next_page = $response['pagination']['nextPageToken'];

            self::dispatch($this->channel_pk, $next_page)->delay(
                now()->addMinutes(1)
            );
        }

        $this->createSyncLog(
            $this->channel_pk, $this->page_token, $response['pagination']
        );
    }

    protected function createSyncLog(int $channel_pk, string $page_token, array $pagination)
    {
        $log = new log;

        $log->channel_pk = $channel_pk;
        $log->page_token = $page_token;
        $log->prev_page_token = $pagination['prevPageToken'] ?? null;
        $log->next_page_token = $pagination['nextPageToken'] ?? null;
        
        $log->total_results = $pagination['totalResults'] ?? null;
        $log->results_per_page = $pagination['resultsPerPage'] ?? null;

        $log->save();
    }

    protected function updateChannelData(channel $channel, object $data) :channel
    {
        $map = [
            'yt_id' => $data->id,
            'name' => $data->snippet->title,
            'video_count' => $data->statistics->videoCount ?? 0,
            'custom_url' => $data->snippet->customUrl ?? null,
        ];

        foreach ($map as $key => $value) {
            if ( $channel->{$key} !== $value ) {
                $channel->{$key} = $value;
            }
        }

        $channel->save();

        return $channel;
    }
}
