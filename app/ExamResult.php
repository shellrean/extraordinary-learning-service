<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    protected $guarded = [];

    public function student()
   	{
   		return $this->belongsTo(User::class,'student_id');
   	}
}
