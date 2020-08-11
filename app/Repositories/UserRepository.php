<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Services\FileService;
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
		try {
			$data = [
				'name'		=> $request->name,
				'email'		=> $request->email,
				'password'	=> bcrypt($request->password),
				'role'		=> $request->role,
				'isactive'	=> $request->isactive,
				'details'	=> $request->details
			];

			$user = User::create($data);
		} catch (\Exception $e) {
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
}