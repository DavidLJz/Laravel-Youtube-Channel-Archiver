<?php

namespace App\Http\Controllers;

use Alaouy\Youtube\Facades\Youtube;
use Illuminate\Http\Request;
use App\Services\YoutubeService;
use App\Jobs\FetchChannelVideosMetadata;
use App\Models\channel;

class YoutubeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

	public function addChannels(Request $request)
	{
        $channels = [];
        $data = $request->only(['id','name']);

        $yt = new YoutubeService;

        foreach ($data as $type => $values) {
            $values = array_filter($values);

            foreach ($values as $val) {
                $r = $yt->getChannel($val, $type);

                if ( empty($r) ) {
                    continue;
                }

                $exists = channel::select(['pk'])->firstWhere('yt_id', $r->id);

                if ( !empty($exists) ) {
                    continue;
                }

                $channel = new channel;
                $channel->yt_id = $r->id;
                $channel->name = $r->snippet->title;
                $channel->video_count = $r->statistics->videoCount ?? 0;
                $channel->custom_url = $r->snippet->customUrl ?? null;
                $channel->save();

                $channels[] = $channel->toArray();

                FetchChannelVideosMetadata::dispatch($channel->pk);
            }
        }

        return response()->json($channels);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
