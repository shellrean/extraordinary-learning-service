<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'settings'
    ];
}
