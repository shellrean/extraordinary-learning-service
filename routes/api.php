<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'namespace' => 'Api\v1'], function() {
	Route::post('login', 'AuthController@login');

	Route::group(['middleware' => 'auth:api'], function() {

		/**
		 |-----------------------------------------------------------------
		 | User route section
		 |-----------------------------------------------------------------
		 */
		Route::get('user-authenticated', 'UserController@getUserLogin');
		Route::post('users/teacher', 'UserController@storeTeacher');
		Route::get('users/teacher', 'UserController@indexTeacher');
		Route::delete('users/{id}', 'UserController@destroy');
		Route::post('users/photo', 'UserController@updatePhoto');
		Route::apiResource('users', 'UserController');

		/**
		 |-----------------------------------------------------------------
		 | Lecture route section
		 |-----------------------------------------------------------------
		 */
		Route::apiResource('lectures', 'LectureController');

		/**
		 |-----------------------------------------------------------------
		 | Subject route section
		 |-----------------------------------------------------------------
		 */
		Route::apiResource('subjects', 'SubjectController');
	});
});