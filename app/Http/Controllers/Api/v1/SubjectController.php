<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\SubjectRepository;
use App\Http\Requests\SubjectRequest;
use App\Http\Requests\SubjectTeacher;
use App\Http\Requests\SubjectImport;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Get data subjects
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param \App\Repositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function index(SubjectRepository $subjectRepository)
    {
    	$perPage = isset(request()->perPage) && request()->perPage == ''
    				? request()->perPage
    				: 10;
    	$search = isset(request()->q) ? request()->q : '';
    	$subjectRepository->getDataSubjects($perPage, $search);
    	return SendResponse::acceptData($subjectRepository->getSubjects());
    }

    /**
     * Create new subject
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param \App\Http\Requests\SubjectRequest $request
     * @param \App\Repositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function store(SubjectRequest $request, SubjectRepository $subjectRepository)
    {
    	$subjectRepository->createNewSubject($request);
    	return SendResponse::acceptData($subjectRepository->getSubject());
    }

    /**
     * Get data subject
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param $subject_id
     * @param \App\Repositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function show($subject_id, SubjectRepository $subjectRepository)
    {
    	$subjectRepository->getDataSubject($subject_id);
    	return SendResponse::acceptData($subjectRepository->getSubject());
    }

    /**
     * Update data subject
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param $subject_id 
     * @param \App\Http\Requests\SubjectRequest $request
     * @param \App\Repositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function update($subject_id, SubjectRequest $request, SubjectRepository $subjectRepository)
    {
    	$subjectRepository->updateDataSubject($request, $subject_id);
    	return SendResponse::acceptData($subjectRepository->getSubject());
    }

    /**
     * Remove data subject
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param $subject_id
     * @param \App\Reqpositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function destroy($subject_id, SubjectRepository $subjectRepository)
    {
    	$subjectRepository->deleteDataSubject($subject_id);
    	return SendResponse::accept('subject deleted');
    }

    /**
     * Get user's subject
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param \App\Repositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function mine(SubjectRepository $subjectRepository)
    {
        $user = request()->user('api');
        $subjectRepository->getDataSubjectsTeacher($user->id);
        return SendResponse::acceptData($subjectRepository->getSubjects());
    }

    /**
     * create data teacher's subject
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\SubjectRepository
     * @return \App\Actions\SendResponse
     */
    public function createNewMine(SubjectTeacher $request, SubjectRepository $subjectRepository)
    {
        $user = request()->user('api');
        $request->teacher_id = $user->id;

        $subjectRepository->createDataSubjectTeacher($request);
        return SendResponse::accept('teacher subject created');
    }

    /**
     * delete data teacher's subject
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\SubjectRepository
     * @return \App\Actions\SendResponse
     */
    public function deleteMine($teacher_subject_id, SubjectRepository $subjectRepository)
    {
        $subjectRepository->deleteDataSubjectTeacher($teacher_subject_id);
        return SendResponse::accept('teacher subject deleted');
    }

    /**
     * Import Subject 
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function import(SubjectImport $request, SubjectRepository $subjectRepository)
    {
        $subjectRepository->importDataSubject($request);
        return SendResponse::accept('subject imported');
    }

    /**
     * Get data teacher's classroom subject
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param $classroom_id
     * @param \App\Repositories\SubjectRepository
     * @return \App\Actions\SendResponse
     */
    public function getTeacherClassroomSubject($classroom_id, SubjectRepository $subjectRepository)
    {
        $user = request()->user('api');
        switch ($user->role) {
            case '1':
                $subjectRepository->getDataClassroomSubject($classroom_id, $user->id);
                break;
            
            default:
                $subjectRepository->getDataClassroomSubject($classroom_id);
                break;
        }
        return SendResponse::acceptData($subjectRepository->getSubjects());
    }
}
