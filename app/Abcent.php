<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abcent extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'details' => 'array'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
