<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $guarded = [];

    public function question_bank()
    {
    	return $this->belongsTo(QuestionBank::class);
    }
}
