<?php

namespace App\Tasks;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Jobs\FetchChannelVideosMetadata;
/**
 * 
 */
class RefetchMetadata
{
    use DispatchesJobs;
	
	function __invoke()
	{
		$yesterday = date('Y-m-d', strtotime('yesterday'));

        $channels = \DB::select(
            'select c.pk, c.name, l.next_page_token ' .
            
            'from channels as c ' .
                
            'left join video_metadata_sync_logs as l ' . 
                'on l.channel_pk = c.pk ' . 
                'where l.next_page_token is null ' . 
                "and l.created_at <= {$yesterday}"
        );

        if (empty($channels)) {
            \Log::debug(__CLASS__ . ': Metadata too recent');
            return;
        }

        foreach ($channels as $c) {
            FetchChannelVideosMetadata::dispatch($c->pk);
        }

        \Log::debug(
            __CLASS__ . ': Fetching metadata from channels', compact('channels')
        );
	}
}