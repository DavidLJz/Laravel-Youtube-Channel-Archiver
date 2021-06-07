<?php

namespace App\Services;

use Alaouy\Youtube\Facades\Youtube;

/**
 * 
 */
class YoutubeService
{
	
	function __construct()
	{
		
	}

	public function getChannel(string $channel, string $type='id') :?object
	{
        $methods = [
            'name' => 'Youtube::getChannelByName',
            'id' => 'Youtube::getChannelById',
        ];

        $method = $methods[$type] ?? false;

        if (!$method) {
        	\Log::error('Channel identifier type not valid', compact('channel','type'));
        	return null;
        }

		try {
			$r = call_user_func($method, $channel);

            if (empty($r)) {
                return null;
            }

            return $r;
		}

        catch (\Throwable $e) {
            \Log::error( $e->__toString() );
            return null;
        }
	}

	public function getVideoList(string $channel_id, string $page_token='', int $count=20) :?array
	{
		$response = Youtube::searchAdvanced([
            'type' => 'video',
            'channelId' => $channel_id,
            'part' => 'id,snippet',
            'maxResults' => $count,
            'order' => 'date',
            'pageToken' => $page_token
        ], true);

		if (empty($response['results'])) {
			return null;
		}

		$data = [ 'videos' => [], 'pagination' => $response['info'] ];

		foreach ($response['results'] as $item) {
			$thumbnail = $item->snippet->thumbnails->high->url;

			$data['videos'][] = [
				'yt_id' => $item->id->videoId,
				'title' => $item->snippet->title,
				'description' => $item->snippet->description,
				'thumbnail' => $thumbnail,
				'publish_time' => $item->snippet->publishTime
			];
		}

		return $data;
	}
}