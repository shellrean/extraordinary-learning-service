<?php

namespace App\Repositories;

use App\ExamSchedule;

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
	public function setSchedule($schedule)
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
			}]);
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
}