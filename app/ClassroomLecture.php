<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomLecture extends Model
{
    protected $guarded = [];

    public function classroom()
    {
    	return $this->belongsTo(Classroom::class);
    }

    public function lecture()
    {
    	return $this->belongsTo(Lecture::class);
    }
}
