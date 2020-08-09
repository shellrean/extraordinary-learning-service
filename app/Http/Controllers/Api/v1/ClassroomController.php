<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\ClassroomRepository;
use App\Http\Requests\ClassroomRequest;
use App\Http\Controllers\Controller;
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
    	$search = isset(request()->q) ? request()->q : ''
    	$classroomRepository->getDataClassrooms($perPage, $search);
    	return SendResponse::acceptData($classroomRepository->getClassroom());
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
    public function update($classroom_id, ClassroomRepository $request, ClassroomRepository $classroomRepository)
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
}
