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
	 * Create new user
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return array
	 */
	public function createNew(UserRequest $request): array
	{
		try {
			$data = [
				'name'		=> $request->name,
				'email'		=> $request->email,
				'password'	=> bcrypt($request->password),
			];

			$user = User::create($data);
		} catch (\Exception $e) {
			return [
				'error' 	=> true, 
				'message' 	=> $e->getMessage(),
				'data' 		=> []
			];
		}
		return [
			'error' 	=> false, 
			'message' 	=> 'user created', 
			'data' 		=> $user
		];
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
	 * Retreive users data 
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getUsers()
	{
		return $this->users;
	}
}