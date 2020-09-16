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
	Route::get('settings/{name}', 'SettingController@show');
	Route::post('users/offline', 'UserController@userOffline')->middleware('auth.res');
	Route::get('report/today', 'AbcentController@reportToday')->middleware('auth.res');

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
		Route::get('lectures/{lecture_id}', 'LectureController@show');
		Route::group(['middleware' => 'auth.teacher'], function() {
			Route::post('lectures/{lecture_id}/sharee', 'LectureController@sharee');
			Route::delete('lectures/sharee/{lecture_share_id}', 'LectureController@deleteSharee');
			Route::apiResource('lectures', 'LectureController')->except('show');
		});

		/**
		 |-----------------------------------------------------------------
		 | Classroom route section
		 |-----------------------------------------------------------------
		 */
		Route::get('classrooms/{classroom_id}/student', 'StudentController@index');
		Route::group(['middleware' => 'auth.teacher'], function() {
			Route::get('classrooms/subject/mine', 'ClassroomController@getTeacherSubject');
			Route::get('classrooms/mine', 'ClassroomController@mine');
			Route::post('classrooms/mine', 'ClassroomController@createNewmine');
			Route::delete('classrooms/mine/{classroom_subject_id}', 'ClassroomController@deleteMine');
			Route::post('classrooms/{classroom_id}/live', 'ClassroomController@storeLiveClassroom');
			Route::post('classrooms/live/{classroom_live_id}/stop', 'ClassroomController@stopLiveClassroom');
			
			Route::post('classrooms/{classroom_id}/student', 'StudentController@store');
			Route::delete('classrooms/student/{student_id}', 'StudentController@destroy');
		});
		Route::get('classrooms/{classroom_id}/subject', 'SubjectController@getTeacherClassroomSubject');
		
		Route::get('classrooms/{classroom_id}/live', 'ClassroomController@liveClassroom');
		Route::get('classrooms/live/{classroom_live_id}', 'ClassroomController@getDataLiveClassroom');

		Route::get('classrooms/live/{classroom_live_id}/comment', 'CommentController@indexClassroomLive');
		Route::post('classrooms/live/{classroom_live_id}/comment', 'CommentController@storeClassroomLive');

		Route::get('classrooms/{classroom_id}/task', 'TaskController@classroomTasks');
		
		Route::get('classrooms/{classroom_id}/lecture', 'LectureController@classroomLectures');

		Route::get('classrooms/{classroom_id}/schedules', 'ScheduleController@scheduleClassroomToday');

		Route::post('classrooms/join', 'ClassroomController@join');
		Route::get('classrooms', 'ClassroomController@index');
		Route::get('classrooms/{classroom_id}', 'ClassroomController@show');
		Route::group(['middleware' => 'auth.admin'], function() {
			Route::post('classrooms/import', 'ClassroomController@import');
			Route::apiResource('classrooms', 'ClassroomController')->except('index','show');
		});

		/**
		 |-----------------------------------------------------------------
		 | Abcent route section
		 |-----------------------------------------------------------------
		 */
		Route::post('abcents', 'AbcentController@store');
		Route::get('abcents/schedule/{schedule_id}', 'AbcentController@scheduleClassroomToday');
		Route::get('abcents/schedule/{schedule_id}/export', 'AbcentController@scheduleClassroomTodayExport');

		/**
		 |-----------------------------------------------------------------
		 | Task route section
		 |-----------------------------------------------------------------
		 */
		Route::post('tasks/{task_id}/collect', 'TaskController@collect');
		Route::get('tasks/{task_id}', 'TaskController@show');
		Route::group(['middleware' => 'auth.teacher'], function() {
			Route::delete('tasks/sharee/{share_task_id}', 'TaskController@deleteSharee');
			Route::post('tasks/{task_id}/sharee', 'TaskController@sharee');
			Route::get('tasks/{task_id}/check', 'TaskController@studentTask');
			Route::get('tasks/{task_id}/result', 'TaskController@taskResult');
			Route::post('tasks/result', 'TaskController@storeTaskResult');
			Route::delete('tasks/student/{task_student_id}', 'TaskController@destroyStudentTask');
			Route::apiResource('tasks', 'TaskController')->except('show');
		});

		/**
		 |-----------------------------------------------------------------
		 | Channel route section
		 |-----------------------------------------------------------------
		 */
		Route::get('channels/{channel_id}/user', 'ChannelController@getDataChannelUser');
		Route::post('channels/{channel_id}/user', 'ChannelController@changeToChannel');

		/**
		 |-----------------------------------------------------------------
		 | Setting route section
		 |-----------------------------------------------------------------
		 */
		Route::group(['middleware' => 'auth.admin'], function() {
			Route::post('settings', 'SettingController@store');
			Route::post('settings/logo', 'SettingController@storeImage');
		});

		/**
		 |-----------------------------------------------------------------
		 | Question bank route section
		 |-----------------------------------------------------------------
		 */
		Route::group(['middleware' => 'auth.teacher'], function() {
			Route::get('question_banks/{question_bank_id}/question', 'QuestionController@indexQuestion');
			Route::get('question_banks/{question_bank_id}/duplicate', 'QuestionController@duplicateQuestionBank');
			Route::post('question_banks/{question_bank_id}/import', 'QuestionController@import');
			Route::post('question_banks/question', 'QuestionController@storeQuestion');
			Route::get('question_banks/question/{question_id}', 'QuestionController@showQuestion');
			Route::put('question_banks/question/{question_id}', 'QuestionController@updateQuestion');
			Route::delete('question_banks/question/{question_id}', 'QuestionController@destroyQuestion');
			Route::apiResource('question_banks', 'QuestionController');
		});

		/**
		 |-----------------------------------------------------------------
		 | Schedule exam bank route section
		 |-----------------------------------------------------------------
		 */
		Route::group(['middleware' => 'auth.teacher'], function() {
			Route::post('exam_schedules/{exam_schedule_id}/status', 'ExamScheduleController@setStatus');
			Route::get('exam_schedules/{exam_schedule_id}/check', 'ResultExamController@uncheckEsay');
			Route::get('exam_schedules/{exam_schedule_id}/result', 'ResultExamController@resultPoint');
			Route::post('exam_schedules/point', 'ResultExamController@storeCheckEsay');
			Route::apiResource('exam_schedules', 'ExamScheduleController');
		});

		/**
		 |-----------------------------------------------------------------
		 | Fileupload bank route section
		 |-----------------------------------------------------------------
		 */
		Route::post('file', 'FileUploadController@store');

		/**
		 |-----------------------------------------------------------------
		 | Information bank route section
		 |-----------------------------------------------------------------
		 */
		Route::get('infos/public', 'InfoController@public_info');
		Route::group(['middleware' => 'auth.admin'], function() {
			Route::apiResource('infos', 'InfoController');
		});

		/**
		 |-----------------------------------------------------------------
		 | Event bank route section
		 |-----------------------------------------------------------------
		 */
		Route::get('events/public', 'EventController@public_event');
		Route::get('events/{event_id}', 'EventController@show');
		Route::group(['middleware' => 'auth.admin'], function() {
			Route::apiResource('events', 'EventController')->except('show');
		});

		/**
		 |-----------------------------------------------------------------
		 | Standart route section
		 |-----------------------------------------------------------------
		 */
		Route::group(['middleware' => 'auth.teacher'], function() {
			Route::apiResource('standarts', 'StandartController');
		});

		/**
		 |-----------------------------------------------------------------
		 | Schedule route section
		 |-----------------------------------------------------------------
		 */
		Route::group(['middleware' => 'auth.teacher'], function() {
			Route::get('schedules/today', 'ScheduleController@scheduleToday');
			Route::get('schedules/{classroom_subject_id}', 'ScheduleController@index');
			Route::get('schedules/{schedule_id}/show', 'ScheduleController@show');
			Route::post('schedules', 'ScheduleController@store');
			Route::put('schedules/{schedule_id}', 'ScheduleController@update');
			Route::delete('schedules/{schedule_id}', 'ScheduleController@destroy');
		});

		/**
		 |-----------------------------------------------------------------
		 | Paper route section
		 |-----------------------------------------------------------------
		 */
		Route::group(['middleware' => 'auth.teacher'], function() {
			Route::post('papers/{paper_id}/update', 'PaperController@update');
			Route::get('papers/{classroom_subject_id}/list', 'PaperController@index');
			Route::apiResource('papers', 'PaperController')->except('update','index');
		});
	});
});

Route::group(['prefix' => 'v2', 'namespace' => 'Api\v2'], function() {
	Route::group(['middleware' => 'auth:api'], function() {
		Route::get('exam_schedules', 'ExamScheduleController@index');
		Route::get('exam_schedules/uncomplete', 'ExamController@uncomplete');
		Route::get('exam_schedules/active', 'ExamController@active');
		Route::post('exam_schedules/exam', 'ExamController@store');
		Route::post('exam_schedules/start', 'ExamController@start');
		Route::get('exam', 'ExamController@indexAnswer');
		Route::post('exam', 'ExamController@storeAnswer');
		Route::post('exam/doubt', 'ExamController@doubtAnswer');
		Route::get('exam/finish', 'ExamController@finishExam');
	});
});