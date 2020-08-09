<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\LectureRepository;
use App\Http\Requests\LectureCreate;
use App\Http\Requests\LectureUpdate;
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
    	return SendResponse::acceptData($lectureRepository->getUser());
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
    	$lectureRepository->getDataLecture($id);
    	$lectureRepository->updateDataLecture($request);
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
    	$lectureRepository->getDataLecutre($id);
    	$lectureRepository->deleteDataLecture();
    	return SendResponse::accept('lecture deleted');
    }
}
