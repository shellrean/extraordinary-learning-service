<?php

namespace App\Actions;

class SendResponse
{
	/**
	 * 400 Bad Request
	 * User send request that can't be handle by server
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \Iluminate\Http\Response
	 */
	public static function badRequest($message = '')
	{
		return response()->json([
			'error' 	=> true,
			'message' 	=> $message != '' ? $message : 'bad request'
		],400);
	}

	/**
	 * 200 Accept
	 * Server send ok response with no data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
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
	 * Server send ok response with data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
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
	 * Server send ok response with custom data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \Illuminate\Http\Response
	 */
	public static function acceptCustom($data)
	{
		return response()->Json($data, 200);
	}

	/**
	 * 500 Server error
	 * Server error when try to process
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \Illuminate\Http\Response
	 */
	public static function serverError($message = '')
	{
		return response()->json([
			'error' 	=> true,
			'message' 	=> $message != '' ? $message : 'server error' 
		], 500);
	}

	/**
	 * 404 Not found
	 * Data not found in server
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \Illuminate\Http\Response
	 */
	public static function notFound($message = '') 
	{
		return response()->json([
			'error' 	=> true,
			'message'	=> $message != '' ? $message : 'not found'
		], 404);
	}

	/**
	  * 403 Forbidden
	  * Not have access to the resource
	  *
	  * @author shellrean <wandinak17@gmail.com>
	  * @since 1.0.0
	  * @return \Illuminate\Http\Response
	  */
	public static function forbidden($message = '')
	{
		return response()->json([
			'error'		=> true,
			'message'	=> $message != '' ? $message : 'you do not have access to the resource'
		], 403);
	}
}