<?php

namespace App\Repositories;

use App\Abcent;

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
	public function getDataAbcentSubjectClassroomToday($subject_id, $classroom_id)
	{
		try {
			$abcents = Abcent::with('user')->where(function($query) use ($subject_id, $classroom_id) {
				$query->where('subject_id', $subject_id)
				->where('classroom_id', $classroom_id)
				->whereHas('user', function($query) {
					$query->where('role','2');
				})
				->whereDate('created_at', \Carbon\Carbon::today());
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
				'subject_id'	=> $request->subject_id,
				'classroom_id'	=> $request->classroom_id,
				'isabcent'		=> $request->isabcent,
				'desc'			=> $request->desc,
				'details'		=> $request->details
			];
			$user = Abcent::where(function($query) use ($request){
				$query->where('subject_id', $request->subject_id)
				->where('classroom_id', $request->classroom_id)
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
			}
			$reports = Abcent::with([
				'user' => function($query) {
					$query->select('id','name','email','role');
				},
				'classroom' => function($query) {
					$query->select('id','teacher_id','name','grade','group');
				},
				'classroom.teacher' => function($query) {
					$query->select('id','name','email');
				},
				'subject' => function($query) {
					$query->select('id','name');
				}
			])
			->where(function($query) use ($date){
				$query->whereDate('created_at', $date)
				->where('isabcent',0);
			})
			->select('id','user_id','subject_id','classroom_id','isabcent','details')
			->get();
			$this->reports = $reports->groupBy('classroom.name');
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}