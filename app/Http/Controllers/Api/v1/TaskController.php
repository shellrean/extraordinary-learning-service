<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;
use App\Http\Requests\TaskCollect;
use App\Http\Requests\TaskUpdate;
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
        $search = isset(request()->q) ? request()->q : '';
    	$user = request()->user('api');
    	$taskRepository->getDataTasks($user->id, $perPage, $search);
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
     * Update data task
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\TaskRepository
     * @param \App\Http\Repositores\TaskUpdate
     * @return \App\Actions\SendResponse
     */
    public function update($task_id, TaskUpdate $request, TaskRepository $taskRepository)
    {
        $taskRepository->updateDataTask($task_id, $request);
        return SendResponse::accept('task updated');
    }

    /**
     * Delete data task
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\TaskRepository
     * @param \App\Http\Request
     * @return \App\Actions\SendResponse
     */
    public function destroy($task_id, TaskRepository $taskRepository)
    {
        $taskRepository->deleteDataTask($task_id);
        return SendResponse::accept('task deleted');
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
        $user = request()->user('api');
    	$request->task_id = $task_id;
        $request->teacher_id = $user->id;

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
        $user = request()->user('api');
        switch ($user->role) {
            case '1':
                $taskRepository->getDataTaskClassroom($classroom_id, $user->id);
                break;
            
            default:
                $taskRepository->getDataTaskClassroom($classroom_id);
                break;
        }
  		return SendResponse::acceptData($taskRepository->getTasks());
  	}

    /**
     * Store collect data task
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\TaskRepository
     * @return \App\Actions\SendResponse
     */
    public function collect($task_id, TaskCollect $request, TaskRepository $taskRepository)
    {
        $user = request()->user('api');
        $request->student_id = $user->id;
        $request->task_id = $task_id;

        if(isset($request->file)) {
            $attach = [];
            $files = $request->file('file');

            if($request->hasFile('file'))
            {
                foreach ($files as $file) {
                    $filename = date('Ymd').'-'.$file->getClientOriginalName();
                    array_push($attach, $filename);
                    $file->storeAs('attachment/', $filename );
                }
                
                $content = [
                    'file'     => $attach
                ];
                $request->merge([ 'content' => $content ]);
            }
        }

        $taskRepository->createNewStudentTask($request);
        return SendResponse::accept('assign collected');
    }
}
