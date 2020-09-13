<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\ClassroomRepository;
use App\Http\Requests\ClassroomStudent;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /** 
     * Get data clasroom's student
     *
     * @author shellrean <wandinak17@gmail.com>
     * @return \App\Actions\SendResponse
     */
    public function index($classroom_id, ClassroomRepository $classroomRepository)
    {
    	$classroomRepository->getDataClassroomStudent($classroom_id);
    	return SendResponse::acceptData($classroomRepository->getClassroomStudents());
    }

    /** 
     * Creat new data clasroom's student
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\ClassroomStudent
     * @return \App\Actions\SendResponse
     */
    public function store($classroom_id, ClassroomStudent $request, UserRepository $userRepository)
    {
        $userRepository->getDataUser($request->uid, 'uid');
        $created = ClassroomRepository::insertStudentToClassroom($userRepository->getUser(), $classroom_id);
        if(!$created) {
            return SendResponse::badRequest('Student has inserted to another classroom');
        }
        return SendResponse::accept('student inserted to classroom');
    }   

    /**
     * Delete student from classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\ClassroomRepository
     * @return void
     */
    public function destroy($student_id, ClassroomRepository $classroomRepository)
    {
        $classroomRepository->deleteStudentFromClassroom($student_id);
        return SendResponse::accept('student deleted from classroom');
    }
}
