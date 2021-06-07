<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class channel extends Model
{
    protected $primaryKey = 'pk';

    public function videos()
    {
        return $this->hasMany('App\Models\video', 'channel_pk');
    }

    public function video_metadata_sync_log()
    {
        return $this->hasMany('App\Models\video_metadata_sync_log', 'channel_pk');
    }
}
