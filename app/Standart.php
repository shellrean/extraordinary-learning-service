<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Standart extends Model
{
    protected $guarded = [];

    public function children()
    {
    	return $this->hasMany(Standart::class,'standart_id');
    }

    
}
