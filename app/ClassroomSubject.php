<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomSubject extends Model
{
    protected $guarded = [];

    public function classroom()
    {
    	return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
    	return $this->belongsTo(Subject::class);
    }
}
