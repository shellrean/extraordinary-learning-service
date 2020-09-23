<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];

    public function classroom_subject()
    {
    	return $this->belongsTo(ClassroomSubject::class);
    }

    public function abcents()
    {
        return $this->hasMany(Abcent::class);
    }
}
