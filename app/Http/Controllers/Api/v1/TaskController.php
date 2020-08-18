<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\ResultTaskStore;
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
        $taskRepository->getDataTask($task_id);
        $task = $taskRepository->getTask();
        if($task->status) {
            return SendResponse::badRequest('You have submited this task'); 
        }
        if($task->deadline < \Carbon\Carbon::now()) {
            return SendResponse::badRequest('Time to submit task has over');
        }

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
                    $file->storeAs('public/attachment/', $filename );
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

    /** 
     * Get data student's task submit
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositores\TaskRepository
     * @return \App\Actions\SendResponse
     */
    public function studentTask($task_id, TaskRepository $taskRepository)
    {
        $classroom_id = isset(request()->c) ? request()->c : '';
        $taskRepository->getDataUncheckedTask($task_id, $classroom_id);
        return SendResponse::acceptData($taskRepository->getTasks());
    }

    /**
     * Store result student's task submit
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\TaskRepository
     * @return \App\Actions\SendResponse
     */
    public function storeTaskResult(ResultTaskStore $request, TaskRepository $taskRepository)
    {
        $taskRepository->getDataTaskResult($request->student_task_id);
        if($taskRepository->getTask()) {
            return SendResponse::badRequest('result has submited before');
        }
        $taskRepository->createNewTaskResult($request);
        return SendResponse::accept('result stored');
    }

    /**
     * Delete data studen's task submit
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\TaskRepository
     * @return \App\Actions\SendResponse
     */
    public function destroyStudentTask($student_task_id, TaskRepository $taskRepository)
    {
        $taskRepository->deleteDataStudentTask($student_task_id);
        return SendResponse::accept('Student task submit deleted');
    }
}
