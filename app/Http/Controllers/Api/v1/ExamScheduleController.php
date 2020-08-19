<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\ExamScheduleRepository;
use App\Http\Requests\ExamScheduleStore;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class ExamScheduleController extends Controller
{
    /**
     * Get exam schedules
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ExamScheduleRepository
     * @return \App\Actions\SendResponse
     */
    public function index(ExamScheduleRepository $examScheduleRepository)
    {
    	$per_page = isset(request()->perPage) && request()->perPage != ''
    				? request()->perPage
    				: 10;
    	$user = request()->user('api');
    	$examScheduleRepository->getDataExamSchedules($per_page, $user->id);
    	return SendResponse::acceptData($examScheduleRepository->getExamSchedules());
    }

    /**
     * Store exam schedule
     *
     * @author shellran <wandinak17@gamil.com>
     * @param \App\Repositories\ExamScheduleRepository
     * @return \App\Actions\SendResponse
     */
    public function store(ExamScheduleStore $request, ExamScheduleRepository $examScheduleRepository)
    {
    	$user = request()->user('api');
    	$request->teacher_id = $user->id;
    	$examScheduleRepository->createDataExamSchedule($request);
    	return SendResponse::accept('exam schedule created');
    }

    /**
     * Get data exam sh
}
