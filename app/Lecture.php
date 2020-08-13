<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'settings'	=> 'casts'
    ];

    public function subject()
    {
    	return $this->belongsTo(Subject::class);
    }
}
