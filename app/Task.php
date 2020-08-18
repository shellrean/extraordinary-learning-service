<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'settings'		=> 'array',
    	'created_at' =>  'datetime:d F Y h : i A'
    ];

    public $appends = ['lastsubmit', 'status'];
    
    public function getLastsubmitAttribute()
    {
    	return \Carbon\Carbon::parse($this->deadline)->format('d F Y h : i A');
    }

    public function getStatusAttribute() 
    {
        $user = request()->user('api');
        if($user->role == '2') {
            $task = StudentTask::where([
                'student_id'    => request()->user('api')->id,
                'task_id'       => $this->id
            ])->first();
            if($task) {
                return true;
            }
            return false;
        }
        return false;
    }
}
