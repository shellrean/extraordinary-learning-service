<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $guarded = [];

    public function question()
    {
    	return $this->belongsTo(question::class);
    }

    
}
