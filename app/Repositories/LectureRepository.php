<?php

namespace App\Repositories;

use App\Lecture;

class LectureRepository
{
	/**
	 * Lectures data
	 * App\Lecture
	 */
	private $lectures;

	/**
	 * Lecture data
	 * App\Lecture
	 */
	private $lecture;

	/**
	 * Retreive lectures data
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return App\Lecture
	 */
	public function getLectures()
	{
		return $this->lectures;
	}

	/**
	 * Retreive lecture data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return App\Lecture
	 */
	public function getLecture()
	{
		return $this->lecture;
	}

	/**
	 * Set lecture data property
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function setLecture(Lecture $lecture)
	{
		return $this->lecture = $lecture;
	}

	/**
	 * Get lectures data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param int $perPage
	 * @param string $search
	 * @param string status
	 * @return void
	 */
	public function getDataLectures(int $perPage, string $search, $isactive = ''): void
	{
		$lectures = Lecture::orderBy('id','desc');
		if ($search != '') {
			$lectures = $lectures->where('title', 'LIKE','%'.$search.'%');
		}
		if ($isactive != '') {
			$lectures = $lectures->where('isactive', $isactive);
		}
		$this->lectures = $lectures->paginate($perPage);
	}

	/**
	 * Get lecture data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getDataLecture($value, string $key = 'id', bool $exception = true)
	{
		$lecture = Lecture::where($key, $value)->first();
		if(!$lecture && $exception) {
			throw new \App\Exceptions\LectureNotFoundException();
		}
		$this->setLecture($lecture);
	}

	/**
	 * Create new lecture data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function createNewLecture($request): void
	{
		try {
			$data = [
				'title'		=> $request->title,
				'body'		=> $request->body,
				'user_id'	=> $request->user_id,
				'subject_id' => $request->subject_id
			];

			$lecture = Lecture::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
		$this->setLecture($lecture);
	}

	/**
	 * Update lecture data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function updateDataLecture($request, $lecture_id = ''): void
	{
		try {
			if($lecture_id != '') {
				$this->getDataLecture($lecture_id);
			}
			$data = [
				'title'		=> $request->title,
				'body'		=> $request->body,
				'subject_id' => $request->subject_id
			];
			$this->lecture->update($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Remove lecture data
	 *
	 * @author shellrean <wandinak17@gmaill.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function deleteDataLecture($lecture_id = ''): void
	{
		try {
			if($lecture_id != '') {
				$this->getDataLecture($lecture_id);
			}
			$this->lecture->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}