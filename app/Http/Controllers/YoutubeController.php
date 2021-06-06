<?php

namespace App\Http\Controllers;

use Alaouy\Youtube\Facades\Youtube;
use Illuminate\Http\Request;
use App\Services\YoutubeService;

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
        $data = $request->only(['ids','names']);

        $yt = new YoutubeService;

        foreach ($data as $type => $values) {
            $values = array_filter($values);

            foreach ($values as $val) {
                $r = $yt->getChannel($val, $type);

                if (empty($r)) {
                    continue;
                }

                $channel = new channel;
                $channel->yt_id = $r->id;
                $channel->name = $r->snippet->title;
                $channel->video_count = $r->statistics->videoCount ?? 0;
                $channel->save();

                $channels[] = $channel->toArray();
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
