<?php

namespace App\Repositories;

use App\Subject;
use App\TeacherSubject;

class SubjectRepository
{
	/**
	 * Data subjects
	 * App\Subject
	 */
	private $subjects;

	/**
	 * Data subject
	 * App\Subject
	 */
	private $subject;

	/**
	 * Retreive data subjects
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return App\Subject
	 */
	public function getSubjects()
	{
		return $this->subjects;
	}

	/**
	 * Retreive data subject
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return App\Subject
	 */
	public function getSubject()
	{
		return $this->subject;
	}

	/**
	 * Set data subject
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param App\Subject
	 */
	public function setSubject(Subject $subject)
	{
		$this->subject = $subject;
	}

	/**
	 * Get data subjects
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param int $perPage
	 * @param string $search
	 * @return void
	 */
	public function getDataSubjects(int $perPage, string $search): void
	{
		try {
			$subjects = Subject::orderBy('name');
			if($search != '') {
				$subjects = $subjects->where('name', 'LIKE', '%'.$search.'%');
			}
			$this->subjects = $subjects->paginate($perPage);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data subjects teacher
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param int $teacher_id
	 * @return void
	 */
	public function getDataSubjectsTeacher(int $teacher_id): void
	{
		try {
			$subjects = TeacherSubject::where('teacher_id', $teacher_id)->get();
			$this->subjects = $subjects;
		} catch (\Exceptions $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data subject
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param string | int $subject_id
	 * @return void
	 */
	public function getDataSubject($subject_id, $key = 'id', bool $exception = true): void
	{
		try {
			$subject = Subject::where($key, $subject_id)->first();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
		if(!$subject && $exception) {
			throw new \App\Exceptions\SubjectNotFoundException();
		}
		$this->setSubject($subject);
	}

	/**
	 * Creat enew data subject
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function createNewSubject($request): void
	{
		try {
			$data = [
				'name'			=> $request->name,
				'description'	=> $request->description,
				'settings'		=> $request->settings
			];	
			$subject = Subject::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
		$this->setSubject($subject);
	}

	/**
	 * Delete data subject
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function deleteDataSubject($subject_id = ''): void
	{
		try {
			if($subject_id != '') {
				$this->getDataSubject($subject_id);
			}
			$this->subject->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data subject
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function updateDataSubject($request, $subject_id = ''): void
	{
		try {
			if($subject_id != '') {
				$this->getDataSubject($subject_id);
			}
			$data = [
				'name'		=> $request->name,
				'description' => $request->description,
				'settings'	=> $request->settings
			];
			$this->subject->update($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}