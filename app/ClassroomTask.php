<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomTask extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'created_at' =>  'datetime:d/m/Y h:i A'
    ];

    public function classroom()
    {
    	return $this->belongsTo(Classroom::class);
    }

    public function task()
    {
    	return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
