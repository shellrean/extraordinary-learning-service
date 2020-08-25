<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $guarded = [];

    public function question()
    {
    	return $this->belongsTo(Question::class);
    }

    public function question_bank()
    {
    	return $this->belongsTo(QuestionBank::class);
    }
}
