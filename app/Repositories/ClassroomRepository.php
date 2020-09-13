<?php

namespace App\Repositories;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Imports\ClassroomImport;
use App\ClassroomSubject;
use App\ClassroomStudent;
use App\ClassroomLive;
use App\Classroom;
use App\Schedule;

class ClassroomRepository
{
	/**
	 * Data classrooms
	 * App\Classroom
	 */
	private $classrooms;

	/**
	 * Data classroom
	 * App\Classroom
	 */
	private $classroom;

	/**
	 * Data subjects
	 * App\ClassroomSubject
	 */
	private $subjects;

	/**
	 * Data classroom's student
	 * App\ClassroomStudent
	 */
	private $classroom_students;

	/**
	 * Data schedules
	 * App\Schedule
	 */
	private $schedules;

	/**
	 * Data schedule
	 * App\Schedule
	 */
	private $schedule;

	/**
	 * Retreive data classrooms
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return App\Classroom
	 */
	public function getClassrooms()
	{
		return $this->classrooms;
	}

	/**
	 * Retreive data subjects
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return App\ClassroomStudent
	 */
	public function getSubjects()
	{
		return $this->subjects;
	}

	/**
	 * Retreive data classroom students
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\ClassroomStudent
	 */
	public function getClassroomStudents()
	{
		return $this->classroom_students;
	}

	/**
	 * Retreive data schedules
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @return Collection
	 */
	public function getSchedules()
	{
		return $this->schedules;
	}

	/**
	 * Retreive data classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return App\Classroom
	 */
	public function getClassroom()
	{
		return $this->classroom;
	}

	/**
	 * Retreive data schedule
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @return App\Schedules
	 */
	public function getSchedule()
	{
		return $this->schedule;
	}

	/**
	 * Set data classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param App\Classroom
	 */
	public function setClassroom(Classroom $classroom)
	{
		$this->classroom = $classroom;
	}

	/**
	 * Set data schedule
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param App\Schedule
	 */
	public function setSchedule(Schedule $schedule)
	{
		$this->schedule = $schedule;
	}

	/**
	 * Get data classrooms
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param int $perPage
	 * @param string $search
	 * @return void
	 */
	public function getDataClassrooms(int $perPage, string $search = '')
	{
		try {
			$classrooms = Classroom::orderBy('grade');
			if($search != '') {
				$classrooms = $classrooms->where('name', 'LIKE', '%'.$search.'%');
			}
			$this->classrooms = $classrooms->paginate($perPage);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param bool $exception
	 * @return void
	 */
	public function getDataClassroom($classroom_id, string $key = 'id', bool $exception = true)
	{
		$classroom = Classroom::where($key, $classroom_id)->first();
		if(!$classroom && $exception) {
			throw new \App\Exceptions\ClassRoomNotFoundException();
		}
		$this->setClassroom($classroom);
	}

	/**
	 * Get data subject classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getDataClasssroomHasSubject($subject_ids) 
	{
		try {
			$classrooms = ClassroomSubject::with(['classroom', 'subject']);
			if(is_array($subject_ids)) {
				$classrooms = $classrooms->whereIn('subject_id', $subject_ids);
			} else {
				$classrooms = $classrooms->where('subject_id', $subject_ids);
			}
			$this->classrooms = $classrooms->get();
		} catch (\Exceptions $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data teacher's classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getDataClassroomTeacher($teacher_id)
	{
		try {
			$classrooms = ClassroomSubject::with(['classroom','subject'])
						->where('teacher_id', $teacher_id)
						->get();
			$this->classrooms = $classrooms;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create new classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 */
	public function createNewClassroom($request)
	{
		try {
			$data = [
				'teacher_id' => $request->teacher_id,
				'name' => $request->name,
				'grade'	=> $request->grade,
				'group'	=> $request->group,
				'settings' => $request->settings,
				'invitation_code' => strtoupper(date('d').uniqid())
			];
			$classroom = Classroom::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
		$this->setClassroom($classroom);
	}

	/**
	 * Updat data classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function updateDataClassroom($request, $classroom_id = ''): void
	{
		try {
			if($classroom_id != '') {
				$this->getDataClassroom($classroom_id);
			}
			$data = [
				'teacher_id' => $request->teacher_id,
				'name' => $request->name,
				'grade'	=> $request->grade,
				'settings' => $request->settings 
			];
			$this->classroom->update($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete data classroom
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function deleteDataClassroom($classroom_id = ''): void
	{
		try {
			if($classroom_id != '') {
				$this->getDataClassroom($classroom_id);
			}
			$this->classroom->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create new classroom live
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function createNewClassroomLive($request)
	{
		try {
			$data = [
				'schedule_id'	=> $request->schedule_id,
				'body'			=> $request->body,
				'settings'		=> $request->settings
			];
			$classroom = ClassroomLive::create($data);
			$this->classroom = $classroom;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data classroom live
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $classroom_id
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	 public function getDataClassroomLives($classroom_id, $teacher_id = '', bool $status = true)
	{
	 	try {
	 		$classrooms = ClassroomLive::with([
	 			'schedule.classroom_subject.subject' => function($query) {
	 				$query->select('id','name');
	 			},
	 			'schedule.classroom_subject.teacher' => function($query) {
	 				$query->select('id','name','email');
	 			}
	 		])
	 		->whereHas('schedule.classroom_subject', function($query) use ($classroom_id, $teacher_id) {
	 			$query->where('classroom_id', $classroom_id);
	 			if($teacher_id != '') {
	 				$query->where('teacher_id', $teacher_id);
	 			}
	 		})
	 		->select('id','schedule_id','created_at')
	 		->where(['isactive', $status,'created_at' => \Carbon\Carbon::now()]);

	 		$this->classrooms = $classrooms->orderBy('id','desc')->get()->map(function($item) {
	 			return [
	 				'classroom_live_id'		=> $item->id,
	 				'classroom_id'			=> $item->schedule->classroom_subject->classroom_id,
	 				'subject_name'			=> $item->schedule->classroom_subject->subject->name,
	 				'teacher_name'			=> $item->schedule->classroom_subject->teacher->name,
	 				'teacher_email'			=> $item->schedule->classroom_subject->teacher->email,
	 				'start_time'			=> $item->created_at->format('H:m')
	 			];
	 		});
	 	} catch (\Exception $e) {
	 		throw new \App\Exceptions\ModelException($e->getMessage());
	 	}
	}

	/**
	 * Set data classroom live status
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $classlive_id
	 * @param bool $status
	 */
	public function setStatusClassroomLive($classlive_id, bool $status)
	{
		try {
			$liveClass = ClassroomLive::find($classlive_id);
			if(!$liveClass) {
				throw new \App\Exceptions\ClassRoomNotFoundException();
			}
			$liveClass->update(['isactive' => $status]);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data classroom live
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $classlive_id
	 */
	public function getDataClassroomLive($classlive_id)
	{
		try {
			$liveClass = ClassroomLive::find($classlive_id);
			if(!$liveClass) {
				throw new \App\Exceptions\ClassRoomNotFoundException();
			}
			$this->classroom = $liveClass;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data classroom's student
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $classroom_id
	 */
	public function getDataClassroomStudent($classroom_id)
	{
		try {
			$students = ClassroomStudent::with('student')->where('classroom_id', $classroom_id)->get();
			$this->classroom_students = $students;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Store data classroom's student
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $request
	 */
	public function createNewClassroomStudent($request)
	{
		try {
			$data = [
				'student_id'	=> $request->student_id,
				'classroom_id'	=> $request->classroom_id,
				'invitation_code' => $request->invitation_code
			];
			ClassroomStudent::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create new user's classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function createNewClassroomTeacher($request)
	{
		try {
			$data = [
				'teacher_id'	=> $request->teacher_id,
				'classroom_id'	=> $request->classroom_id,
				'subject_id'	=> $request->subject_id
			];
			ClassroomSubject::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete user's classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function deleteDataClassroomTeacher($classroom_subject_id)
	{
		try {
			ClassroomSubject::where('id', $classroom_subject_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/** 
	 * Import classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $request
	 */
	public function importDataClassroom($request)
	{
		DB::beginTransaction();

		try {
			Excel::import(new ClassroomImport, $request->file('file'));
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw new \App\Exceptions\ModelException($e->getMessage());	
		}
	}

	/**
	 * Join classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $request
	 * @return void
	 */
	public function joinClassroomStudent($request)
	{
		try {
			$classroom = Classroom::where('invitation_code', $request->invitation_code)->first();
			$data = [
				'student_id'	=> $request->student_id,
				'classroom_id' => $classroom->id,
				'invitation_code' => $request->invitation_code
			];
			ClassroomStudent::create($data);
		} catch (\Exception $e) {	
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get teacher's subject
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $teacher_id
	 * @return void
	 */
	public function getDataTeacherSubject($teacher_id, $classroom_id = '')
	{
		try {
			$subjects = ClassroomSubject::with(['subject' => function($query) {
				$query->select('id','name');
			}])
			->where('teacher_id', $teacher_id);
			if($classroom_id != '') {
				$subjects = $subjects->where('classroom_id', $classroom_id);
			}
			$this->subjects = $subjects->get();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data schedules
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param $classroom_subject_id
	 * @return void
	 */
	public function getDataSchedules($classroom_subject_id)
	{
		try {
			$schedules = Schedule::where('classroom_subject_id', $classroom_subject_id)->orderBy('from_time')->get();
			$this->schedules = $schedules;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data schedule
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param $schedule_id
	 * @return void
	 */
	public function getDataSchedule($schedule_id, bool $exception = true)
	{
		try {
			$schedule = Schedule::where('id', $schedule_id)->first();
			if(!$schedule && $exception) {
				throw new \App\Exceptions\ModelNotFoundException($e->getMessage());
			}
			$this->setSchedule($schedule);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create data schedules
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param $request
	 * @return void
	 */
	public function createNewSchedule($request)
	{
		try {
			$schedule = Schedule::create([
				'classroom_subject_id'	=> $request->classroom_subject_id,
				'day'					=> $request->day,
				'from_time'				=> $request->from_time,
				'end_time'				=> $request->end_time
			]);
			$this->setSchedule($schedule);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data schedule
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param $schedule_id
	 * @param $request
	 * @return void
	 */
	public function updateDataSchedule($request, $schedule_id = '')
	{
		try {
			if($schedule_id != '') {
				$this->getDataSchedule($schedule_id);
			}
			$this->schedule->update([
				'classroom_subject_id'	=> $request->classroom_subject_id,
				'day'					=> $request->day,
				'from_time'				=> $request->from_time,
				'end_time'				=> $request->end_time
			]);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get schedule 
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param $day_of_week
	 * @param $teacher_id
	 * @return void
	 */
	public function getDataSchedulesDay($day_of_week, $teacher_id)
	{
		try {
			$schedules = Schedule::with([
				'classroom_subject' => function($query) {
					$query->select('id','classroom_id','subject_id');
				},
				'classroom_subject.classroom' => function($query) {
					$query->select('id','name','group','grade');
				},
				'classroom_subject.subject' => function($query) {
					$query->select('id','name');
				}
			])
			->where('day', $day_of_week)
			->whereHas('classroom_subject', function($query) use($teacher_id) {
				$query->where('teacher_id', $teacher_id);
			})
			->select('id','classroom_subject_id','from_time','end_time')
			->get();

			$this->schedules = $schedules->map(function($item) {
				return [
					'schedule_id'		=> $item->id,
					'classroom_name'	=> $item->classroom_subject->classroom->name,
					'subject_name'		=> $item->classroom_subject->subject->name,
					'from_time'			=> $item->from_time,
					'end_time'			=> $item->end_time
				];
			});
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get schedule classroom's today
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.1
	 * @param $day_of_week
	 * @param $classroom_id
	 * @return void
	 */
	public function getdataSchedulesClassroomDay($day_of_week, $classroom_id, $teacher_id = null)
	{
		try {
			$schedules = Schedule::with([
				'classroom_subject' => function($query) {
					$query->select('id','classroom_id','subject_id');
				},
				'classroom_subject.classroom' => function($query) {
					$query->select('id','name','group','grade');
				},
				'classroom_subject.subject' => function($query) {
					$query->select('id','name');
				}
			])
			->where('day',$day_of_week)
			->whereHas('classroom_subject', function($query) use ($classroom_id, $teacher_id) {
				$query->where('classroom_id', $classroom_id);
				if($teacher_id != null) {
					$query->where('teacher_id', $teacher_id);
				}
			})
			->select('id','classroom_subject_id','from_time','end_time')
			->get();

			$this->schedules = $schedules->map(function($item) {
				return [
					'schedule_id'		=> $item->id,
					'classroom_name'	=> $item->classroom_subject->classroom->name,
					'subject_name'		=> $item->classroom_subject->subject->name,
					'from_time'			=> $item->from_time,
					'end_time'			=> $item->end_time
				];
			});
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete data schedule
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param $schedule_id
	 * @return void
	 */
	public function deleteDataSchedule($schedule_id)
	{
		try {
			Schedule::where('id',$schedule_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}