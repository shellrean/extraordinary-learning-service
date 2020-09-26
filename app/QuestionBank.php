<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'percentage'	=> 'array'
    ];

    public function subject()
    {
    	return $this->belongsTo(Subject::class)->select('id','name');
    }

    public function standart()
    {
        return $this->belongsTo(Standart::class);
    }
}
