<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'settings'	=> 'array',
    	'addition'	=> 'array',
    	'created_at' =>  'datetime:d F Y G : i',
    ];

    public function subject()
    {
    	return $this->belongsTo(Subject::class);
    }
}
