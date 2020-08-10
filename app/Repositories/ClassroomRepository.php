<?php

namespace App\Repositories;

use App\Classroom;

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
				'settings' => $request->settings 
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
}