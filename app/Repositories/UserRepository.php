<?php

namespace App\Repositories;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserRequest;
use App\Imports\TeacherImport;
use App\Imports\StudentImport;
use App\Services\FileService;
use App\ClassroomStudent;
use App\User;

class UserRepository 
{
	/**
	 * App\User 
	 */
	private $users = [];

	/**
	 * App\User
	 */
	private $user;

	/**
	 * Retreive users data 
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\User
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * Retreive user data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Set user property
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Create new user
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return array
	 */
	public function createNew(UserRequest $request): void
	{
		DB::beginTransaction();
		try {
			$data = [
				'name'		=> $request->name,
				'email'		=> $request->email,
				'password'	=> bcrypt($request->password),
				'role'		=> $request->role,
				'isactive'	=> $request->isactive,
				'uid'		=> $request->uid,
				'details'	=> $request->details
			];

			$user = User::create($data);
			if($request->role == '2' && $request->classroom_id != '') {
				ClassroomStudent::create([
					'student_id'	=> $user->id,
					'classroom_id'	=> $request->classroom_id
				]);
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
		$this->setUser($user);
	}

	/**
	 * Get user data table
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getDataUsers(int $perPage, string $search, string $role = '1'): void
	{
		$users = User::where('role', $role);
		if ($search != '') {
			$users = $users->where('name', 'LIKE','%'.$search.'%');
		}
		$this->users = $users->paginate($perPage);
	}

	/**
	 * Get user data by id
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getDataUser($value, string $key = 'id'): void
	{
		$user = User::where($key,$value)->first();
		if(!$user) {
			throw new \App\Exceptions\UserNotFoundException();
		}
		$this->user = User::where($key,$value)->first();
	}

	/**
	 * Remove data user
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return array
	 */
	public function deleteDataUser(): void
	{
		if($this->user instanceof User) {
			try {
				$this->user->delete();
			} catch (\Exception $e) {
				throw new \App\Exceptions\ModelException($e->getMessage());
			}
		}
	}

	/**
	 * Update photo profile
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function updatePhoto($filename): void
	{
		$detail = $this->user->details;
		if(is_array($detail)) {
			if(isset($detail['avatar'])) {
				FileService::remove('app/public/'.$detail['avatar']);
				unset($detail['avatar']);
			}
			$detail['avatar'] = $filename;
		} else {
			$detail = [
				'avatar' => $filename
			];
		}

		try {
			$this->user->details = $detail;
			$this->user->save();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update user data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function updateDataUser($request): void
	{
		$data = [
			'name'			=> $request->name,
			'email'			=> $request->email,
			'isactive'		=> $request->isactive,
			'uid'			=> $request->uid,
			'details'		=> $request->details
		];
		if(isset($request->password) && $request->password != '') { 
			$data['password'] = bcrypt($request->password);
		}
		$this->user->update($data);
	}

	/**
	 * Set user online
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function updateDataOnlineUser($request, $user_id = ''): void
	{
		try {
			if($user_id != '') {
				$this->getDataUser($user_id);
			}
			$this->user->update([
				'isonline'	=> $request->isonline
			]);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Import teacher form excel file
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function importTeacher($request)
	{
		DB::beginTransaction();

		try {
			Excel::import(new TeacherImport, $request->file('file'));

			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Import student form excel file
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function importStudent($request)
	{
		DB::beginTransaction();

		try {
			Excel::import(new StudentImport($request->classroom_id), $request->file('file'));

			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get user classroom
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getStudentClassroom($student_id, bool $exception = false)
	{
		try {
			$classroom = ClassroomStudent::with('classroom')
					->where('student_id', $student_id)
					->first();
			return $classroom;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * get data not in list
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getUserNotInData($request)
	{
		try {
			if(is_array($request->ids)){
				$users = User::whereNotIn('id', $request->ids)->get();
			} else {
				$users = User::orderBy('role')->get();
			}
			$this->users = $users;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update profile data
	 *
	 * @author shellran <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param $request
	 * @return void
	 */
	public function updateDataProfile($request, $user_id = '')
	{
		try {
			if($user_id != '') {
				$this->getDataUser($user_id);
			}
			$data = array();
			if(isset($request->name)) {
				$data['name'] = $request->name;
			}
			if(isset($request->password) && $request->password != '') {
				$data['password'] = bcrypt($request->password);
			}
			$this->user->update($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}