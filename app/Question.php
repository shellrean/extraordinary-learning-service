<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    protected $hidden = [
    	'created_at', 'updated_at'
    ];

    public function options()
    {
    	return $this->hasMany(QuestionOption::class);
    }
}
