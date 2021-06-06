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
            \Log::error($e->getMessage());
            return null;
        }
	}

	public function getVideoList(string $channel_id, int $count=20) :?array
	{
		$list = Youtube::listChannelVideos($channel_id, $count, 'date');

		if (empty($list)) {
			return null;
		}

		$videos = [];

		foreach ($list as $item) {
			$thumbnail = $item->snippet->thumbnails->high->url;

			$videos[] = [
				'yt_id' => $item->id->videoId,
				'title' => $item->snippet->title,
				'description' => $item->snippet->description,
				'thumbnail' => $thumbnail,
				'publish_time' => $item->snippet->publishTime
			];
		}

		return $videos;
	}
}