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
		Route::get('logout', 'AuthController@logout');
		/**
		 |-----------------------------------------------------------------
		 | User route section
		 |-----------------------------------------------------------------
		 */
		Route::get('user-authenticated', 'UserController@getUserLogin');
		Route::post('users/online', 'UserController@setOnlineUser');

		Route::post('users/photo', 'UserController@updatePhoto');
		Route::group(['middleware' => 'auth.admin'], function() {
			Route::post('users/teacher', 'UserController@storeTeacher');
			Route::get('users/teacher', 'UserController@indexTeacher');
			Route::post('users/student', 'UserController@storeStudent');
			Route::get('users/student', 'UserController@indexStudent');
			Route::delete('users/{id}', 'UserController@destroy');
			Route::apiResource('users', 'UserController')->except('index');
		});

		/**
		 |-----------------------------------------------------------------
		 | Subject route section
		 |-----------------------------------------------------------------
		 */
		Route::get('subjects/mine', 'SubjectController@mine')->middleware('auth.teacher');
		Route::apiResource('subjects', 'SubjectController')->middleware('auth.admin');

		/**
		 |-----------------------------------------------------------------
		 | Lecture route section
		 |-----------------------------------------------------------------
		 */
		Route::get('lectures/{lecture_id}/comment', 'CommentController@index');
		Route::post('lectures/{lecture_id}/comment', 'CommentController@store');
		Route::apiResource('lectures', 'LectureController');

		/**
		 |-----------------------------------------------------------------
		 | Classroom route section
		 |-----------------------------------------------------------------
		 */
		Route::get('classrooms/mine', 'ClassroomController@mine')->middleware('auth.teacher');
		Route::apiResource('classrooms', 'ClassroomController')->middleware('auth.admin');

		/**
		 |-----------------------------------------------------------------
		 | Abcent route section
		 |-----------------------------------------------------------------
		 */
		Route::get('abcents/subject/{subject_id}/classroom/{classroom_id}/today', 'AbcentController@subjectClassroomToday');
		Route::post('abcents/subject/{subject_id}/classroom/{classroom_id}/today', 'AbcentController@store');

		/**
		 |-----------------------------------------------------------------
		 | Channel route section
		 |-----------------------------------------------------------------
		 */
		Route::get('channels/{channel_id}/user', 'ChannelController@getDataChannelUser');
		Route::post('channels/{channel_id}/user', 'ChannelController@changeToChannel');
	});
});