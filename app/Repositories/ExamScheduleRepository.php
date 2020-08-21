<?php

namespace App\Repositories;

use App\ExamSchedule;
use App\StudentExam;

class ExamScheduleRepository
{
	/**
	 * Data exam schedule
	 * @var App\ExamSchedule
	 */
	private $schedule;

	/** 
	 * Data exam schedules
	 * @var Collection
	 */
	private $schedules;

	/**
	 * Data exam schedule student completed
	 * @var Collection
	 */
	public $schedule_completed;

	/**
	 * Retreive data exam schedule
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return App\ExamSchedule
	 */
	public function getExamSchedule()
	{
		return $this->schedule;
	}

	/**
	 * Retreive data exam schedules
	 *
	 * @author shellraen <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return Collection
	 */
	public function getExamSchedules()
	{
		return $this->schedules;
	}

	/**
	 * Set data exam schedule
	 *
	 * @author shelllrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param App\ExamSchedule
	 * @return void
	 */
	public function setExamSchedule($schedule)
	{
		$this->schedule = $schedule;
	}

	/**
	 * Get data exam schedules
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $request
	 * @return void
	 */
	public function getDataExamSchedules(int $per_page, $teacher_id = '')
	{
		try {
			$schedules = ExamSchedule::with(['question_bank' => function($query) {
				$query->select('id','code');
			}])->orderBy('id','desc');
			if($teacher_id != '') {
				$schedules = $schedules->where('teacher_id', $teacher_id);
			}
			$this->schedules = $schedules->paginate($per_page);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create data exam schedule
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $request
	 * @return void
	 */
	public function createDataExamSchedule($request)
	{
		try {
			$data = [
				'question_bank_id'	=> $request->question_bank_id,
				'teacher_id'	=> $request->teacher_id,
				'classrooms' => $request->classrooms,
				'name'	=> $request->name,
				'date' => $request->date,
				'start_time' => $request->start_time,
				'duration' => $request->duration*60,
				'setting' => $request->setting
			];
			ExamSchedule::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data exam Schedule
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $exam_schedule_id
	 * @return void
	 */
	public function getDataExamSchedule($exam_schedule_id, bool $exception = true)
	{
		try {
			$schedule = ExamSchedule::find($exam_schedule_id);
			if(!$schedule && $exception) {
				throw new \App\Exceptions\ModelNotFoundException('exam schedule not found');
			}
			$this->setExamSchedule($schedule);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data exam schedule
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $exam_schedule_id
	 * @param \App\Http\Requests\ExamScheduleStore
	 * @return void
	 */
	public function updateDataExamSchedule($exam_schedule_id, $request)
	{
		try {
			$this->getDataExamSchedule($exam_schedule_id);
			$data = [
				'question_bank_id'	=> $request->question_bank_id,
				'classrooms' => $request->classrooms,
				'name'	=> $request->name,
				'date' => $request->date,
				'start_time' => $request->start_time,
				'duration' => $request->duration*60,
				'setting' => $request->setting
			];
			$this->schedule->update($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete data exam schedule
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $exam_schedule_id
	 * @return void
	 */
	public function deleteDataExamSchedule($exam_schedule_id)
	{
		try {
			ExamSchedule::where('id', $exam_schedule_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/** 
	 * Update dataexam schedule
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $request
	 * @return void
	 */
	public function updateStatusExamSchedule($exam_schedule_id, $request)
	{
		try {
			$this->getDataExamSchedule($exam_schedule_id);
			$this->schedule->update([
				'isactive' => $request->isactive
			]);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get active exam schedule
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $classroom_id
	 * @return void
	 */
	public function getDataExamScheduleActive($teacher_id = '')
	{
		try {
			$schedules = ExamSchedule::where('isactive', 1);
			if($teacher_id != '') {
				$schedules = $schedules->where('teacher_id', $teacher_id);
			}
			$this->schedules = $schedules->get();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data student's exam has finished
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $student_id
	 * @return void
	 */
	public function getDataExamScheduleStudentFinished($student_id)
	{
		try {
			$has_completed = StudentExam::where([
				'student_id'		=> $student_id,
				'status'	=> 1
			])->get();

			$this->schedule_completed = $has_completed;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data student's exam schedule uncomplete
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $student_id
	 * @return void
	 */
	public function uncompleteExamScheduleStudent($student_id)
	{
		try {
			$this->getDataExamScheduleActive();
			$schedule_ids = $this->schedules->pluck('id')->toArray();

			$schedule = StudentExam::where(function($query) use ($student_id, $schedule_ids) {
				$query->where('student_id', $student_id)
					  ->where('status', 3)
					  ->whereIn('exam_schedule_id', $schedule_ids);
			})->first();

			$this->setExamSchedule($schedule);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data student's exam schedule active
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $student_id
	 * @return void
	 */
	public function activeExamScheduleStudent($student_id, $schedule_ids = '')
	{
		try {
			if($schedule_ids == '') {
				$this->getDataExamScheduleActive();
				$schedule_ids = $this->schedules->pluck('id')->toArray();
			}

			$schedule = StudentExam::where(function($query) use ($student_id, $schedule_ids) {
				$query->where('student_id', $student_id)
					  ->where('status', 0)
					  ->whereIn('exam_schedule_id', $schedule_ids);
			})->first();
			$this->setExamSchedule($schedule);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create data student's exam schedule
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $data
	 */
	public function createDataExamScheduleStudent($data)
	{
		try {
			StudentExam::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Start student's exam schedule
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $student_id
	 * @return void
	 */
	public function startDataExamScheduleStudent($student_id)
	{
		try {
			$this->activeExamScheduleStudent($student_id);
			$this->schedule->update([
				'start' 	=> now()->format('H:i:s'),
				'status'	=> 3
			]);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}