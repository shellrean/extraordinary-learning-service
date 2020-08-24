<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abcent extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'details' => 'array'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function classroom()
    {
    	return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
    	return $this->belongsTo(Subject::class);
    }
}
