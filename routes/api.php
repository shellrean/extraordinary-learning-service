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
			Route::post('users/teacher/import', 'UserController@importTeacher');
			Route::post('users/teacher', 'UserController@storeTeacher');
			Route::get('users/teacher', 'UserController@indexTeacher');
			Route::post('users/student', 'UserController@storeStudent');
			Route::post('users/student/import', 'UserController@importStudent');
			Route::get('users/student', 'UserController@indexStudent');
			Route::delete('users/{id}', 'UserController@destroy');
			Route::apiResource('users', 'UserController')->except('index');
		});

		/**
		 |-----------------------------------------------------------------
		 | Subject route section
		 |-----------------------------------------------------------------
		 */
		Route::group(['middleware' => 'auth.teacher'], function() {
			Route::get('subjects/mine', 'SubjectController@mine');
			Route::post('subjects/mine', 'SubjectController@createNewMine');
			Route::delete('subjects/mine/{teacher_subject_id}', 'SubjectController@deleteMine');
		});
		Route::get('subjects', 'SubjectController@index');
		Route::group(['middleware' => 'auth.admin'], function() {
			Route::post('subjects/import', 'SubjectController@import');
			Route::apiResource('subjects', 'SubjectController')->except('index');
		});

		/**
		 |-----------------------------------------------------------------
		 | Lecture route section
		 |-----------------------------------------------------------------
		 */
		Route::get('lectures/{lecture_id}/comment', 'CommentController@indexLecture');
		Route::post('lectures/{lecture_id}/comment', 'CommentController@storeLecture');
		Route::post('lectures/{lecture_id}/sharee', 'LectureController@sharee');
		Route::get('lectures/classrooms/{classroom_id}', 'LectureController@classroomLectures');
		Route::apiResource('lectures', 'LectureController');

		/**
		 |-----------------------------------------------------------------
		 | Classroom route section
		 |-----------------------------------------------------------------
		 */
		Route::get('classrooms/mine', 'ClassroomController@mine')->middleware('auth.teacher');
		Route::get('classrooms/{classroom_id}/live', 'ClassroomController@liveClassroom');
		Route::post('classrooms/{classroom_id}/live', 'ClassroomController@storeLiveClassroom')->middleware('auth.teacher');
		Route::post('classrooms/live/{classroom_live_id}/stop', 'ClassroomController@stopLiveClassroom')->middleware('auth.teacher');
		Route::get('classrooms/live/{classroom_live_id}', 'ClassroomController@getDataLiveClassroom');

		Route::get('classrooms/live/{classroom_live_id}/comment', 'CommentController@indexClassroomLive');
		Route::post('classrooms/live/{classroom_live_id}/comment', 'CommentController@storeClassroomLive');

		Route::get('classrooms/{classroom_id}/student', 'StudentController@index');
		Route::post('classrooms/{classroom_id}/student', 'StudentController@store');

		Route::get('classrooms/{classroom_id}/task', 'TaskController@classroomTasks');

		Route::post('classrooms/import', 'ClassroomController@import');
		Route::get('classrooms', 'ClassroomController@index');
		Route::apiResource('classrooms', 'ClassroomController')->except('index')->middleware('auth.admin');

		/**
		 |-----------------------------------------------------------------
		 | Abcent route section
		 |-----------------------------------------------------------------
		 */
		Route::post('abcents', 'AbcentController@store');
		Route::get('abcents/subject/{subject_id}/classroom/{classroom_id}/today', 'AbcentController@subjectClassroomToday');
		Route::get('abcents/subject/{subject_id}/classroom/{classroom_id}/export', 'AbcentController@subjectClassroomTodayExport');

		/**
		 |-----------------------------------------------------------------
		 | Task route section
		 |-----------------------------------------------------------------
		 */
		Route::post('tasks/{task_id}/sharee', 'TaskController@sharee');
		Route::post('tasks/{task_id}/collect', 'TaskController@collect');
		Route::apiResource('tasks', 'TaskController');

		/**
		 |-----------------------------------------------------------------
		 | Channel route section
		 |-----------------------------------------------------------------
		 */
		Route::get('channels/{channel_id}/user', 'ChannelController@getDataChannelUser');
		Route::post('channels/{channel_id}/user', 'ChannelController@changeToChannel');
	});
});