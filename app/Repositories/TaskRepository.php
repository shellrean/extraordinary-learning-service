<?php

namespace App\Repositories;

use App\Task;
use App\Classroom;
use App\ResultTask;
use App\StudentTask;
use App\ClassroomTask;
use App\Services\TelegramService;
use Illuminate\Support\Facades\DB;

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
			$tasks = Task::where('user_id',$user_id)->where('title','LIKE','%'.$search.'%')->orderBy('id','desc')->paginate($perPage);
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
			$tasks = ClassroomTask::with(['task' =>  function($query) {
				$query->select('id', 'title');
			}])
			->where('classroom_id', $classroom_id)
			->orderBy('id','desc');
			
			if($teacher_id != '') {
				$tasks = $tasks->where('teacher_id', $teacher_id);
			}
			$tasks = $tasks->select('id','task_id','body','created_at')->paginate(10);
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
			$data = [];
			if(is_array($request->classroom_id)) {
				foreach ($request->classroom_id as $key => $value) {
					$new_data = [
						'classroom_id'	=> $value,
						'task_id'		=> $request->task_id,
						'teacher_id'	=> $request->teacher_id,
						'body'			=> $request->body,
						'created_at'	=> now(),
						'updated_at'	=> now()
					];
					array_push($data, $new_data);
				}
			} else {
				$new_data = [
					'classroom_id'	=> $request->classroom_id,
					'task_id'		=> $request->task_id,
					'teacher_id'	=> $request->teacher_id,
					'body'			=> $request->body,
					'created_at'	=> now(),
					'updated_at'	=> now()
				];
				array_push($data, $new_data);
			}

			DB::table('classroom_tasks')->insert($data);
			$task = ClassroomTask::where([
				'task_id' 	=> $request->task_id,
				'teacher_id'	=> $request->teacher_id
			])
			->whereDate('created_at', now())
			->first();

			if(is_array($request->classroom_id)) {
				$classrooms = Classroom::whereIn('id', $request->classroom_id)->get();

				foreach($classrooms as $classroom) {
					if(isset($classroom->settings['telegram_id'])) {
						TelegramService::sendNotifTask($task, $classroom->settings['telegram_id']);
					}
				}
			} else {
				$classroom = Classroom::find($request->classroom_id);
				if(isset($classroom->settings['telegram_id'])) {
					TelegramService::sendNotifTask($task, $classroom->settings['telegram_id']);
				}
			}
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

	/**
	 * Get data students task
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $task_id
	 * @param $classroom_id
	 * @return void
	 */
	public function getDataUncheckedTask($task_id, $classroom_id = '')
	{
		try {
			$tasks = StudentTask::with('student')
					->doesntHave('result')
					->where('task_id', $task_id);
			if($classroom_id != '') {
				$tasks = $tasks->whereHas('classroom', function($query) use($classroom_id) {
					$query->where('classroom_id', $classroom_id);
				});
			}
			$this->tasks = $tasks->get();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete data students task
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $student_task_id
	 * @return void
	 */
	public function deleteDataStudentTask($student_task_id)
	{
		try {
			StudentTask::where('id', $student_task_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create new data result task
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function createNewTaskResult($request)
	{
		try {
			$data = [
				'student_task_id'	=> $request->student_task_id,
				'point'				=> $request->point
			];
			ResultTask::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data result task
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function getDataTaskResult($student_task_id)
	{
		try {
			$task = ResultTask::where('student_task_id', $student_task_id)->first();
			$this->setTask($task);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data task results
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function getDataTaskResults($task_id, $classroom_id)
	{
		try {
			$tasks = StudentTask::with(['student','result'])
					->whereHas('result')
					->where('task_id', $task_id);
			if($classroom_id != '') {
				$tasks = $tasks->whereHas('classroom', function($query) use($classroom_id) {
					$query->where('classroom_id', $classroom_id);
				});
			}
			$this->tasks = $tasks->get();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getmessage());
		}
	}

	/**
	 * Delete dat atask sharee
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $sharee_task_id
	 * @return void
	 */
	public function deleteShareeTask($sharee_task_id)
	{
		try {
			ClassroomTask::where('id', $sharee_task_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getmessage());
		}
	}
}