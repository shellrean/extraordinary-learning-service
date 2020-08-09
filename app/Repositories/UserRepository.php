<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
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
	public function createNew(UserRequest $request): User
	{
		try {
			$data = [
				'name'		=> $request->name,
				'email'		=> $request->email,
				'password'	=> bcrypt($request->password),
			];

			$user = User::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
		return $user;
	}

	/**
	 * Get user data table
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getDataUsers(int $perPage, string $search, string $role = '2'): void
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
	public function getDataUser(int $value, string $key = 'id'): void
	{
		$user = User::where($key,$value)->first();
		if($user) {
			$this->user = User::where($key,$value)->first();
			return;
		}
		throw new \App\Exceptions\UserNotFoundException();
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
			return;
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
			'details'		=> $request->details
		];
		if(isset($request->password) && $request->password != '') { 
			$data['password'] = bcrypt($request->password);
		}
		$this->user->update($data);
	}
}