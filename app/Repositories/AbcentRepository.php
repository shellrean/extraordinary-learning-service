<?php

namespace App\Repositories;

use App\Abcent;
use App\Schedule;

class AbcentRepository 
{
	/**
	 * Data abcents
	 * App\Abcent
	 */
	private $abcents;

	/**
	 * Data abcent
	 * App\Abcent
	 */
	private $abcent;

	/**
	 * Data reports
	 * @var Collection
	 */
	private $reports;

	/** 
	 * Retreive data abcents
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\Abcent
	 */
	public function getAbcents()
	{
		return $this->abcents;
	}

	/**
	 * Retreive data abcent
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\Abcent
	 */
	public function getAbcent()
	{
		return $this->abcent;
	}

	/**
	 * Retreive data reports
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return Collection
	 */
	public function getReports()
	{
		return $this->reports;
	}

	/**
	 * Set data abcent
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\Abcent
	 */
	public function setAbcent(Abcent $abcent)
	{
		$this->abcent = $abcent;
	}

	/**
	 * Get data absent
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @return void
	 */
	public function getDataAbcent($abcent_id, bool $exception = true)
	{
		try {
			$abcent = Abcent::where('id', $abcent_id)->first();
			if(!$abcent_id && $exception) {
				throw new \App\Exceptions\ModelNotFoundException('abcent not found');
			}
			$this->setAbcent($abcent);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data subject's abcent today
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getDataAbcentSubjectToday($subject_id)
	{
		try {
			$abcents = Abcent::where(function($query) use ($subject_id) {
				$query->where('subject_id', $subject_id)
				->whereDate('created_at', \Carbon\Carbon::today());
			})->get();
			$this->abcents = $abcents;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data subject's classroom abcent
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getDataAbcentScheduleDay($schedule_id, $date = '')
	{
		try {
			if($date == '') {
				$date = \Carbon\Carbon::today();
			}
			$abcents = Abcent::with('user')
			->where(function($query) use ($schedule_id, $date) {
				$query->where('schedule_id', $schedule_id)
				->whereHas('user', function($query) {
					$query->where('role','2');
				})
				->whereDate('created_at', $date);
			})->get();
			$this->abcents = $abcents;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create new Abcent
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function createNewAbcent($request):void
	{
		try {
			$data = [
				'user_id'		=> $request->user_id,
				'schedule_id'	=> $request->schedule_id,
				'isabcent'		=> $request->isabcent,
				'reason'		=> $request->reason,
				'desc'			=> $request->desc,
				'details'		=> $request->details
			];
			$user = Abcent::where(function($query) use ($request){
				$query->where('schedule_id', $request->schedule_id)
				->whereDate('created_at', \Carbon\Carbon::today())
				->where('user_id', $request->user_id);
			})->first();
			if($user) {
				return;
			}
			$this->setAbcent(Abcent::create($data));
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data absent
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param $abcent_id
	 * @param $request
	 * @return void
	 */
	public function updateDataAbcent($request, $abcent_id = '')
	{
		try {
			if($abcent_id != '') {
				$this->getDataAbcent($abcent_id);
			}
			$this->abcent->update([
				'isabcent'		=> $request->isabcent,
				'reason'		=> $request->reason,
				'desc'			=> $request->desc,
				'details'		=> $request->details
			]);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data report
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getProblemToday($date = '')
	{
		try {
			if($date == '') {
				$date = \Carbon\Carbon::today();
				$day = $date->dayOfWeek;
			} else {
				$day = $date->dayOfWeek;
			}
			
			$repots = Schedule::with([
				'classroom_subject' => function($query) {
					$query->select('id','classroom_id','subject_id','teacher_id');
				},
				'classroom_subject.classroom',
				'classroom_subject.subject' => function($query) {
					$query->select('id','name');
				},
				'classroom_subject.teacher' => function($query) {
					$query->select('id','name','uid');
				},
				'abcents' => function($query) use($date) {
					$query->select('id','user_id','schedule_id','isabcent')
					->whereDate('created_at', $date);
				},
				'abcents.user' => function($query) {
					$query->select('id','name','uid','role');
				}
			])
			->where('day', $day)
			->select('id','classroom_subject_id','from_time','end_time')
			->orderBy('from_time')
			->get();
			$this->reports = $repots;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}