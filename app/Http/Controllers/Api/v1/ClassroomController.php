<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\ClassroomRepository;
use App\Repositories\SubjectRepository;
use App\Http\Requests\ClassroomTeacher;
use App\Http\Requests\ClassroomRequest;
use App\Http\Requests\ClassroomImport;
use App\Http\Requests\ClassroomLive;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomJoin;
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
        $classroomRepository->getDataClassroomTeacher($user->id);
        return SendResponse::acceptData($classroomRepository->getClassrooms());
    }

    /**
     * Create data user's classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository
     * @param \App\Actions\SendResponse
     */
    public function createNewMine(ClassroomTeacher $request, ClassroomRepository $classroomRepository)
    {
        $user = request()->user('api');
        $request->teacher_id = $user->id;
        $classroomRepository->createNewClassroomTeacher($request);
        return SendResponse::accept("teacher's data created");
    }

    /**
     * Delete data user's classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository
     * @param \App\Actions\SendResponse
     */
    public function deleteMine($classroom_subject_id, ClassroomRepository $classroomRepository)
    {
        $classroomRepository->deleteDataClassroomTeacher($classroom_subject_id);
        return SendResponse::accept("teacher's data deleted");
    }

    /**
     * Import data classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository
     * @param \App\Actions\SendResponse
     */
    public function import(ClassroomImport $request, ClassroomRepository $classroomRepository)
    {
        $classroomRepository->importDataClassroom($request);
        return SendResponse::accept('class imported');
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
        switch ($user->role) {
            case '1':
                $classroomRepository->getDataClassroomLives($classroom_id, $user->id);
                break;
            
            default:
                $classroomRepository->getDataClassroomLives($classroom_id);
                break;
        }
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

    /**
     * Join classroom
     *
     * @author shellrean <wandinak17@gmila.com>
     * @param \App\Repositories\ClassroomRepository
     * @param \App\Http\Requests\ClassroomJoin
     * @return \App\Actions\SendResponse
     */
    public function join(ClassroomJoin $request, ClassroomRepository $classroomRepository)
    {
        $user = request()->user('api');
        if($user->role != '2') {
            return SendResponse::forbidden();
        }
        $request->student_id = $user->id;
        $classroomRepository->joinClassroomStudent($request);
        return SendResponse::accept('join success');
    }

    /**
     * Get teacher teacher
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ClassroomRepository
     * @return \App\Actions\SendResponse
     */
    public function getTeacherSubject(ClassroomRepository $classroomRepository)
    {
        $user = request()->user('api');
        $classroomRepository->getDataTeacherSubject($user->id);
        $data = $classroomRepository->getSubjects()->map(function($item) {
            return $item->subject;
        })->unique('id');
        return SendResponse::acceptData($data);
    }
}
