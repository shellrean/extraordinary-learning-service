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
     * @author shellrean <wandinak17@gamil.com>
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
     * Get data exam schedule
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ExamSheduleRepository
     * @param $exam_schedule_id
     * @return \App\Actions\SendResponse
     */
    public function show($exam_schedule_id, ExamScheduleRepository $examScheduleRepository)
    {
    	$examScheduleRepository->getDataExamSchedule($exam_schedule_id);
    	return SendResponse::acceptData($examScheduleRepository->getExamSchedule());
    }

    /**
     * Update data exam schedule
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ExamScheduleRepository
     * @param $exam_schedule_id
     * @param \App\Http\Requests\ExamScheduleStore
     * @return \App\Actions\SendResponse
     */
    public function update($exam_schedule_id, ExamScheduleStore $request, ExamScheduleRepository $examScheduleRepository)
    {
    	$examScheduleRepository->updateDataExamSchedule($exam_schedule_id, $request);
    	return SendResponse::acceptData($examScheduleRepository->getExamSchedule());
    }

    /**
     * Delete data exam schedule
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ExamScheduleRepository
     * @param $exam_schedule_id
     * @return \App\Actions\SendResponse
     */
    public function destroy($exam_schedule_id, ExamScheduleRepository $examScheduleRepository)
    {
    	$examScheduleRepository->deleteDataExamSchedule($exam_schedule_id);
    	return SendResponse::accept('exam schedule deleted');
    }

    /**
     * Set status exam schedule
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Repositories\ExamScheduleRepository
     * @param $exam_schedule_id
     * @return \App\Actions\SendResponse
     */
    public function setStatus($exam_schedule_id, Request $request, ExamScheduleRepository $examScheduleRepository)
    {
    	$examScheduleRepository->updateStatusExamSchedule($exam_schedule_id, $request);
    	return SendResponse::accept('status changed');
    }
}
