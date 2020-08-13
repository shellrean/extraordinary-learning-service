<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomStudent extends Model
{
    protected $guarded = [];

    public function student()
    {
    	return $this->belongsTo(User::class, 'student_id');
    }

    public function classroom()
    {
    	return $this->belongsTo(Classroom::class);
    }
}
