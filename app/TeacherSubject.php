<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    protected $guarded = [];

    public function teacher()
    {
    	return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
    	return $this->belongsTo(Subject::class);
    }
}
