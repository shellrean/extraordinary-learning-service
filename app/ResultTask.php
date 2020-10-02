<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultTask extends Model
{
    protected $guarded = [];

    public function student_task()
    {
        return $this->belongsTo(StudentTask::class);
    }
}
