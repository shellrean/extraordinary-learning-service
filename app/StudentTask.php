<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentTask extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'content'		=> 'array',
        'created_at' =>  'datetime:d F Y h : i A'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function result()
    {
    	return $this->hasOne(ResultTask::class, 'student_task_id');
    }

    public function classroom()
    {
    	return $this->hasOne(ClassroomStudent::class, 'student_id', 'student_id');
    }
}
