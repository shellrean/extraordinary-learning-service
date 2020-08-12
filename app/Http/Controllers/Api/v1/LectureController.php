<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\LectureRepository;
use App\Http\Requests\LectureCreate;
use App\Http\Requests\LectureUpdate;
use App\Http\Requests\LectureSharee;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class LectureController extends Controller
{
    /**
     * Get lectures data
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\LectureRepository $lectureRepository
     * @return \App\Actions\SendResponse
     */
    public function index(LectureRepository $lectureRepository)
    {
    	$perPage = isset(request()->perPage) && request()->perPage != '' 
    				? request()->perPage 
    				: 10;
    	$search = isset(request()->q) ? request()->q : '';
    	$status = isset(request()->status) ? request()->status : '';

    	$lectureRepository->getDataLectures($perPage, $search, $status);
    	return SendResponse::acceptData($lectureRepository->getLectures());
    }

    /**
     * Create new lecture data
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Requests\LectureCreate
     * @return \App\Repositories\LectureRepository $lectureRepository
     * @return \App\Actions\SendResponse
     */
    public function store(LectureCreate $request, LectureRepository $lectureRepository)
    {
    	$user = request()->user('api');
    	$request->user_id = $user->id;

    	$lectureRepository->createNewLecture($request);
    	return SendResponse::acceptData($lectureRepository->getLecture());
    }

    /**
     * Show lecture data
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $id
     * @param \App\Repositories\LectureRepository $lectureRepository
     */
    public function show($id, LectureRepository $lectureRepository)
    {
    	$lectureRepository->getDataLecture($id);
    	return SendResponse::acceptData($lectureRepository->getLecture());
    }

    /**
     * Update lecture data
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $id
     * @param \App\Repositories\LectureRepository $lectureRepository
     * @param \App\Http\Requests\LectureUpdate
     * @return \App\Actions\SendResponse
     */
    public function update($id,  LectureUpdate $request, LectureRepository $lectureRepository)
    {
    	$lectureRepository->updateDataLecture($request, $id);
    	return SendResponse::acceptData($lectureRepository->getLecture());
    }

    /**
     * Remove lecture data
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $id
     * @param \App\Repositories\LectureRepository $lectureRepository
     * @return \App\Actions\SendResponse
     */
    public function destroy($id, LectureRepository $lectureRepository)
    {
    	$lectureRepository->deleteDataLecture($id);
    	return SendResponse::accept('lecture deleted');
    }

    /**
     * Sharee lecture data to classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $lecture_id
     * @param \App\Repositories\LectureRepository
     * @return \App\Actions\SendResponse
     */
    public function sharee($lecture_id, LectureSharee $request, LectureRepository $lectureRepository) 
    {
        $user = request()->user('api');
        $request->teacher_id = $user->id;
        $request->lecture_id = $lecture_id;

        $lectureRepository->createNewLectureClassroom($request);
        return SendResponse::accept('lecture shared');
    }

    /**
     * Get data classroom's lectures
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $classroom_id
     * @param \App\Reqpositories\LectureRepository
     * @return \App\Actions\SendResponse
     */
    public function classroomLectures($classroom_id, LectureRepository $lectureRepository)
    {
        $user = request()->user('api');
        $lectureRepository->getDataLecturesClassroom($classroom_id, $user->id);
        return SendResponse::acceptData($lectureRepository->getLectures());
    }
}
