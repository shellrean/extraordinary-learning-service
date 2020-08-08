<?php

namespace App\Actions;

class SendResponse
{
	/**
	 * 400 Bad Request
	 *
	 * @return \Iluminate\Http\Response
	 */
	public static function badRequest($message = '')
	{
		return response()->json([
			'error' 	=> true,
			'message' 	=> $message != '' ? $message : 'bad request'
		]);
	}

	/**
	 * 200 Accept
	 *
	 * @return \Illuminate\Http\Response
	 */
	public static function accept($message = '')
	{
		return response()->json([
			'error' 	=> false,
			'message' 	=> $message != '' ? $message : 'success'
		], 200);
	}

	/**
	 * 200 Accept Data
	 *
	 * @return \Illuminate\Http\Response
	 */
	public static function acceptData($data) 
	{
		return response()->json([
			'error'		=> false,
			'data'		=> $data
		], 200);
	}

	/**
	 * 200 Custom data
	 *
	 * @return \Illuminate\Http\Response
	 */
	public static function acceptCustom($data)
	{
		return response()->Json($data, 200);
	}

	/**
	 * 500 Server error
	 *
	 * @return \Illuminate\Http\Response
	 */
	public static function serverError($message = '')
	{
		return response()->json([
			'error' 	=> true,
			'message' 	=> $message != '' ? $message : 'server error' 
		], 500);
	}
}