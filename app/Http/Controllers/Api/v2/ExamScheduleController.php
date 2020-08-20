<?php

namespace App\Http\Controllers\Api\v2;

use App\Repositories\ExamScheduleRepository;
use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    /**
     * Get student's schedule
     *
     * @author shellrean <wandinak17@gmail.com>
     * @return \App\Actions\SendResponse
     */
    public function index(ExamScheduleRepository $examScheduleRepository, UserRepository $userRepository)
    {
    	$student = request()->user('api');
    	$classroom = $userRepository->getStudentClassroom($student->id, true);

    	$examScheduleRepository->getDataExamScheduleStudentFinished($student->id);
    	$has_complete = $examScheduleRepository->schedule_completed->pluck('exam_schedule_id');

    	$examScheduleRepository->getDataExamScheduleActive();
    	$schedules = $examScheduleRepository->getExamSchedules();

    	$use = $schedules->reject(function($value) use ($classroom, $has_complete) {
    		return !in_array($classroom->classroom_id, array_column($value->classrooms,'id')) 
    			|| in_array($value->id, $has_complete->toArray());
    	});

    	if(!$use) {
    		return SendResponse::acceptData([]);
    	}
    	return SendResponse::acceptData($use);
    }
}
