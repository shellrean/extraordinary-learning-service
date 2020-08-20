<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'classrooms'	=> 'array',
    	'setting'	=> 'array'
    ];

    public function question_bank()
    {
    	return $this->belongsTo(QuestionBank::class);
    }
}
