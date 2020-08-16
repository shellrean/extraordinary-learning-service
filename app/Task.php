<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'settings'		=> 'array',
    	'created_at' =>  'datetime:d F Y G : i',
    	'lastsubmit'	=> 'datetime:d F Y G : i'
    ];

    public $appends = ['lastsubmit'];

    public function getLastsubmitAttribute()
    {
    	return $this->lastsubmit;
    }
}
