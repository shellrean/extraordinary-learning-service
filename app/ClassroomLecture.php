<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomLecture extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'created_at' =>  'datetime:d/m/Y h:i A'
    ];

    public function classroom()
    {
    	return $this->belongsTo(Classroom::class);
    }

    public function lecture()
    {
    	return $this->belongsTo(Lecture::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
