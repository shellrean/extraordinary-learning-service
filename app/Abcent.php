<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abcent extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'details' => 'array'
    ];
}
