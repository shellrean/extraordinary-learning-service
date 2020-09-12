<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\ClassroomRepository;
use App\Http\Requests\ScheduleStore;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Get data schedules
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param $classroom_subject_id
     * @param \App\Repositories\ClassroomRepository
     * @return \App\Actions\SendResponse
     */
    public function index($classroom_subject_id, ClassroomRepository $classroomReposiory)
    {
    	$classroomReposiory->getDataSchedules($classroom_subject_id);
    	return SendResponse::acceptData($classroomReposiory->getSchedules());
    }

    /**
     * Get data schedule
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param $schedule_id
     * @param \App\Repositories\ClassroomRepository
     * @return \App\Actions\SendResponse
     */
    public function show($schedule_id, ClassroomRepository $classroomReposiory)
    {
        $classroomReposiory->getDataSchedule($schedule_id);
        return SendResponse::acceptData($classroomReposiory->getSchedule());
    }

    /**
     * Create data schedule
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Requests\ScheduleStore
     * @param \App\Repositories\ClassroomRepository
     * @return \App\Actions\SendResponse
     */
    public function store(ScheduleStore $request, ClassroomRepository $classroomReposiory)
    {
    	$classroomReposiory->createNewSchedule($request);
    	return SendResponse::acceptData($classroomReposiory->getSchedule());
    }

    /**
     * Update data schedule
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Requests\ScheduleStore
     * @param $schedule_id
     * @param \App\Repositories\ClassroomRepository
     * @return \App\Actions\SendResponse
     */
    public function update($schedule_id, ScheduleStore $request, ClassroomRepository $classroomRepository)
    {
    	$classroomRepository->updateDataSchedule($request, $schedule_id);
    	return SendResponse::acceptData($classroomRepository->getSchedule());
    }

    /**
     * Destroy data schedule
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param $chedule_id
     * @param \App\Repositories\ClassroomRepository
     * @return \App\Actions\SendResponse
     */
    public function destroy($schedule_id, ClassroomRepository $classroomReposiory)
    {
    	$classroomReposiory->deleteDataSchedule($schedule_id);
    	return SendResponse::accept('schedule deleted');
    }

    /**
     * Schedule today
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository
     * @return \App\Actions\SendResponse
     */
    public function scheduleToday(ClassroomRepository $classroomReposiory)
    {
        $teacher = request()->user('api');
        $day_of_week = date('w');
        $classroomReposiory->getDataSchedulesDay($day_of_week, $teacher->id);
        return SendResponse::acceptData($classroomReposiory->getSchedules());
    }
}
