<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomLive extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'settings'	=> 'array'
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }
}
