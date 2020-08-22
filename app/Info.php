<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'settings' => 'array',
    	'created_at' =>  'datetime:d F Y h : i A'
    ];
}
