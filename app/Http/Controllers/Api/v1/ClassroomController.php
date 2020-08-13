<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\ClassroomRepository;
use App\Repositories\SubjectRepository;
use App\Http\Requests\ClassroomRequest;
use App\Http\Requests\ClassroomLive;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Get data classrooms
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository $classroomRepository
     * @return \App\Actions\SendResponse
     */
    public function index(ClassroomRepository $classroomRepository)
    {
    	$perPage = isset(request()->perPage) && request()->perPage != ''
    				? request()->perPage
    				: 10;
    	$search = isset(request()->q) ? request()->q : '';
    	$classroomRepository->getDataClassrooms($perPage, $search);
    	return SendResponse::acceptData($classroomRepository->getClassrooms());
    }

    /**
     * Crate new classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository $classroomRepository
     * @param \App\Actions\SendResponse
     */
    public function store(ClassroomRequest $request, ClassroomRepository $classroomRepository)
    {
    	$classroomRepository->createNewClassroom($request);
    	return SendResponse::acceptData($classroomRepository->getClassroom());
    }

    /**
     * Get data classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param $classroom_id
     * @param \App\Repositories\ClassroomRepository $classroomRepository
     * @return \App\Actions\SendResponse
     */
    public function show($classroom_id, ClassroomRepository $classroomRepository)
    {
    	$classroomRepository->getDataClassroom($classroom_id);
    	return SendResponse::acceptData($classroomRepository->getClassroom());
    }

    /**
     * Update data classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param $classroom_id
     * @param \App\Repositories\ClassroomRepository $classroomRepository
     * @return \App\Actions\SendResponse
     */
    public function update($classroom_id, ClassroomRequest $request, ClassroomRepository $classroomRepository)
    {
    	$classroomRepository->updateDataClassroom($request, $classroom_id);
    	return SendResponse::acceptData($classroomRepository->getClassroom());
    }

    /**
     * Delete data classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param $classroom_id
     * @param \App\Repositories\ClassroomRepository $classroomRepository
     * @return \App\Actions\SendResponse
     */
    public function destroy($classroom_id, ClassroomRepository $classroomRepository)
    {
    	$classroomRepository->deleteDataClassroom($classroom_id);
    	return SendResponse::accept();
    }

    /**
     * Get data user's classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository
     * @param \App\Repositories\SubjectRepository
     * @return \App\Actions\SendResponse
     */
    public function mine(ClassroomRepository $classroomRepository, SubjectRepository $subjectRepository)
    {
        $user = request()->user('api');
        $subjectRepository->getDataSubjectsTeacher($user->id);
        $classroomRepository->getDataClasssroomHasSubject(
            $subjectRepository->getSubjects()->pluck('subject_id')->toArray()
        );
        return SendResponse::acceptData($classroomRepository->getClassrooms());
    }

    /**
     * Get data classroom's live
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository
     * @param $classroom_id
     * @return \App\Actions\SendResponse
     */
    public function liveClassroom($classroom_id, ClassroomRepository $classroomRepository)
    {
        $user = request()->user('api');
        $classroomRepository->getDataClassroomLives($classroom_id, $user->id);
        return SendResponse::acceptData($classroomRepository->getClassrooms());
    }

    /**
     * Create new classroom's live
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository
     * @param $classroom_id
     * @return \App\Actions\SendResponse
     */
    public function storeLiveClassroom($classroom_id, ClassroomLive $request, ClassroomRepository $classroomRepository)
    {
        $user = request()->user('api');
        $request->teacher_id = $user->id;
        $request->classroom_id = $classroom_id;

        $classroomRepository->createNewClassroomLive($request);
        return SendResponse::acceptData($classroomRepository->getClassroom());
    }

    /**
     * Stop classroom live
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository
     * @param $classlive_id
     * @return \App\Actions\SendResponse
     */
    public function stopLiveClassroom($classlive_id, ClassroomRepository $classroomRepository)
    {
        $classroomRepository->setStatusClassroomLive($classlive_id, false);
        return SendResponse::accept('live class stopped');
    }

    /**
     * Get data classroom live
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository
     * @param $classlive_id
     * @return \App\Actions\SendResponse
     */
    public function getDataLiveClassroom($classlive_id, ClassroomRepository $classroomRepository)
    {
        $classroomRepository->getDataClassroomLive($classlive_id);
        return SendResponse::acceptData($classroomRepository->getClassroom());
    }
}
