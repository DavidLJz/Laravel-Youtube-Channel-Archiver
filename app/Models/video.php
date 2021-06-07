<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class video extends Model
{
    protected $primaryKey = 'pk';
    protected $fillable = ['name','yt_id','description'];

    public function channel()
    {
        return $this->belongsTo('App\Models\channel', 'channel_pk');
    }
}
