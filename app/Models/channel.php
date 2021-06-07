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
}
