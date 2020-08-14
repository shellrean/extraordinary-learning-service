<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentTask extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'content'		=> 'array'
    ];
}
