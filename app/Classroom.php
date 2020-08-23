<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    	'settings'		=> 'array'
    ];  

    protected $hidden = [
        'invitation_code','created_at','updated_at'
    ];
}
