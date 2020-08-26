<?php

namespace App\Repositories;

use App\Lecture;
use App\ClassroomLecture;
use Illuminate\Support\Facades\DB;

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
	public function getDataLectures($user_id, int $perPage, string $search, $isactive = ''): void
	{
		$lectures = Lecture::with('subject')->orderBy('id','desc')->where('user_id', $user_id);
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
				'subject_id' => $request->subject_id,
				'addition' => $request->addition
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
	 * @param $lecture_id <optional>
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
	 * @param $lecture_id <optional>
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

	/**
	 * Create new classroom lecture
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $request
	 * @return void
	 */
	public function createNewLectureClassroom($request)
	{
		try {
			$data  = [];
			if(is_array($request->classroom_id)) {
				foreach ($request->classroom_id as $key => $value) {
					array_push($data, [
						'teacher_id' => $request->teacher_id,
						'lecture_id' => $request->lecture_id,
						'classroom_id' => $value,
						'body' => $request->body,
						'created_at' => now(),
						'updated_at' => now()
					]);
				}
				DB::table('classroom_lectures')->insert($data);
			} else {
				$data = [
					'teacher_id' => $request->teacher_id,
					'lecture_id' => $request->lecture_id,
					'classroom_id' => $request->classroom_id,
					'body' => $request->body
				];
				ClassroomLecture::create($data);
			}
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}


	/**
	 * Get data lecture classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $classroom_id
	 * @return void
	 */
	public function getDataLecturesClassroom($classroom_id, $teacher_id = '')
	{
		try {
			$lectures = ClassroomLecture::with(['lecture','lecture.subject'])->where('classroom_id', $classroom_id);
			if($teacher_id != '') {
				$lectures = $lectures->where('teacher_id', $teacher_id);
			}
			$this->lectures = $lectures->orderBy('id','desc')->paginate(10);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete data lecture sharee 
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $sharee_lecture_id
	 * @return void
	 */
	public function deleteDataLectureSharee($sharee_lecture_id)
	{
		try {
			ClassroomLecture::where('id', $sharee_lecture_id)->delete();
		} catch (\Exceptions $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}