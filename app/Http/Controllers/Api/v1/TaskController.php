<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;
use App\Http\Requests\TaskSharee;
use App\Http\Requests\TaskCreate;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Get data tasks
     *
     * @author shellrean <wandinak17@gmial.com>
	 * @param \App\Repositories\TaskRepository
     * @return \App\Actions\SendResponse
     */
    public function index(TaskRepository $taskRepository)
    {
    	$perPage = isset(request()->perPage) && request()->perPage != ''
    				? request()->perPage
    				: 10;
    	$user = request()->user('api');
    	$taskRepository->getDataTasks($user->id, $perPage);
    	return SendResponse::acceptData($taskRepository->getTasks());
    }

    /** 
     * Get data task
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\TaskRepository
     * @param $task_id
     * @return \App\Actions\SendResponse
     */
    public function show($task_id, TaskRepository $taskRepository)
    {
    	$taskRepository->getDataTask($task_id);
    	return SendResponse::acceptData($taskRepository->getTask());
    }

    /**
     * Store data task
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\TaskRepository
     * @param \App\Http\Request
     * @return \App\Actions\SendResponse
     */
    public function store(TaskCreate $request, TaskRepository $taskRepository)
    {
    	$user = request()->user('api');
    	$request->user_id = $user->id;

    	$taskRepository->createNewTask($request);
    	return SendResponse::acceptData($taskRepository->getTask());
    }

    /**
     * Share task to classroom
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\TaskRepository
     * @param $task_id
     * @return \App\Actions\SendRepose
     */
    public function sharee($task_id, TaskSharee $request, TaskRepository $taskRepository)
    {
    	$request->task_id = $task_id;

    	$taskRepository->createNewTaskClassroom($request);
    	return SendResponse::accept('task sharared');
    }

  	/**
  	 * Get data classroom's task
  	 *
  	 * @author shellrean <wandinak17@gmail.com>
  	 * @param \App\Repositories\TaskRepository
  	 * @return \App\Actions\SendResponse
  	 */
  	public function classroomTasks($classroom_id, TaskRepository $taskRepository)
  	{
  		$taskRepository->getDataTaskClassroom($classroom_id);
  		return SendResponse::acceptData($taskRepository->getTasks());
  	}
}
