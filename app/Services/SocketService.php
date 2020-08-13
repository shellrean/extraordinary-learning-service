<?php

namespace App\Services;

use App\User;
use \Illuminate\Support\Facades\DB;

class SocketService 
{
	/**
	 * Get user login in room
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $chanel
	 * @return \Illuminate\Support\Facades\DB
	 */
	public function getDataUserOnChanel($channel) 
	{
		try {
			$users = DB::table('users')->where('channel', $channel)->get()->map(function($item) {
				return [
					'id' => $item->id,
					'name' => $item->name,
					'email' => $item->email,
					'role' => $item->role
				];
			});
			if(!$users) {
				return [];
			}
			return $users;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Put user to channel
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $user
	 * @param $channel_id
	 * @return void
	 */
	public function setUserToChannel($user_id, $channel_id)
	{
		try {
			DB::table('users')
	            ->where('id', $user_id)
	            ->update(['channel' => $channel_id]);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}