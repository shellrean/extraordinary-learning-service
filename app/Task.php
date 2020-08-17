<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'settings'		=> 'array',
    	'created_at' =>  'datetime:d F Y h : i A'
    ];

    public $appends = ['lastsubmit'];

    public function getLastsubmitAttribute()
    {
    	return $this->deadline->format('d F Y h : i A');
    }
}
