<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassroomTask extends Model
{
    protected $guarded = [];

    public function classroom()
    {
    	return $this->belongsTo(Classroom::class);
    }

    public function task()
    {
    	return $this->belongsTo(Task::class);
    }
}
