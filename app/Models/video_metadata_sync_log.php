<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class video_metadata_sync_log extends Model
{
    protected $primaryKey = 'pk';

    public function channel()
    {
        return $this->belongsTo('App\Models\channel', 'channel_pk');
    }
}
