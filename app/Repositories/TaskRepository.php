<?php

namespace App\Repositories;

use App\Task;
use App\StudentTask;
use App\ClassroomTask;

class TaskRepository
{
	/**
	 * Data Tasks
	 * \App\Task
	 */
	private $tasks;

	/**
	 * Data Task
	 * \App\Task
	 */
	private $task;

	/**
	 * Retreive data tasks
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\Task
	 */
	public function getTasks()
	{
		return $this->tasks;
	}

	/**
	 * Retreive data task
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\Task
	 */
	public function getTask()
	{
		return $this->task;
	}

	/**
	 * Set task property
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \App\Task $task
	 * @return void
	 */
	public function setTask($task)
	{
		$this->task = $task;
	}

	/**
	 * Get data tasks
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $user_id
	 * @param $perPage
	 * @return void
	 */
	public function getDataTasks($user_id, int $perPage, $search = '')
	{
		try {
			$tasks = Task::where('user_id',$user_id)->where('title','LIKE','%'.$search.'%')->paginate($perPage);
			$this->tasks = $tasks;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data task
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $task_id
	 */
	public function getDataTask($task_id, bool $exception = true)
	{
		try {
			$task = Task::find($task_id);
			if(!$task && $exception) {
				throw new \App\Exceptions\TaskNotFoundException();
			}
			$this->setTask($task);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create new data task
	 *
	 * @author shellran <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function createNewTask($request) 
	{
		try {
			$data = [
				'user_id'		=> $request->user_id,
				'title'			=> $request->title,
				'body'			=> $request->body,
				'type'			=> $request->type,
				'deadline'		=> $request->deadline,
				'isactive'		=> $request->isactive,
				'settings'		=> $request->settings
			];
			$task = Task::create($data);
			$this->setTask($task);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data task
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function updateDataTask($task_id, $request)
	{
		try {
			$this->getDataTask($task_id);
			$data = [
				'title'		=> $request->title,
				'body'		=> $request->body,
				'type'		=> $request->type,
				'deadline'		=> $request->deadline,
			];
			$this->task->update($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete data task
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $task_id
	 * @return void
	 */
	public function deleteDataTask($task_id)
	{
		try {
			Task::where('id', $task_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data classroom's task
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $classroom_id
	 * @return void
	 */
	public function getDataTaskClassroom($classroom_id, $teacher_id = '')
	{
		try {
			$tasks = ClassroomTask::with('task')
					->where('classroom_id', $classroom_id)
					->orderBy('id','desc');
			if($teacher_id != '') {
				$tasks = $tasks->where('teacher_id', $teacher_id);
			}
			$tasks = $tasks->paginate(10);
			$this->tasks = $tasks;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create new classroom's task
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $request
	 * @return void
	 */
	public function createNewTaskClassroom($request)
	{
		try {
			$data = [
				'classroom_id'	=> $request->classroom_id,
				'task_id'		=> $request->task_id,
				'teacher_id'	=> $request->teacher_id,
				'body'			=> $request->body
			];
			$task = ClassroomTask::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Collect student's task
	 *
	 * @author shellran <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $request
	 * @return void
	 */
	public function createNewStudentTask($request)
	{
		try {
			$data = [
				'student_id'	=> $request->student_id,
				'task_id'		=> $request->task_id,
				'content'		=> $request->content
			];
			$task = StudentTask::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}