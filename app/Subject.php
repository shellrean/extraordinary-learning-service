<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'settings'		=> 'array'
    ];
}
