<?php

namespace App\Http\Controllers\Api\v2;

use App\Repositories\ExamScheduleRepository;
use App\Repositories\ExamStudentRepository;
use App\Repositories\QuestionRepository;
use App\Http\Requests\ExamStudentStore;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Get data uncomplete student's exam
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Respositories\ExamRepository
     * @return \App\Actions\SendResponse 
     */
    public function uncomplete(ExamScheduleRepository $examScheduleRepository)
    {
    	$student = request()->user('api');

    	$examScheduleRepository->uncompleteExamScheduleStudent($student->id);
    	$data = $examScheduleRepository->getExamSchedule();
    	if(!$data) {
    		$data = [];
    	}
    	return SendResponse::acceptData($data);
    }

    /**
     * Get data student's exam active
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repository ExamRepository
     * @return \App\Actions\SendResponse
     */
    public function active(ExamScheduleRepository $examScheduleRepository)
    {
    	$student = request()->user('api');

    	$examScheduleRepository->activeExamScheduleStudent($student->id);
    	$data = $examScheduleRepository->getExamSchedule();
    	if(!$data) {
    		$data = [];
    	}
    	return SendResponse::acceptData($data);
    }

    /**
     * Create student's exam
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Http\Requests\ExamStudentStore
     * @param \App\Repositories\ExamScheduleRepository
     * @return \App\Actions\SendResponse
     */
    public function store(ExamStudentStore $request, ExamScheduleRepository $examScheduleRepository)
    {
    	$student = request()->user('api');
    	$examScheduleRepository->getDataExamSchedule($request->exam_schedule_id);
    	$schedule = $examScheduleRepository->getExamSchedule();
    	
    	$examScheduleRepository->activeExamScheduleStudent($student->id, [$schedule->id]);
    	$data = $examScheduleRepository->getExamSchedule();

    	if($data) {
    		return SendResponse::accept('exam started');
    	}

    	$data = [
    		'student_id'		=> $student->id,
    		'exam_schedule_id' 	=> $schedule->id,
    		'start'				=> '',
    		'remaining' 		=> $schedule->duration,
    		'status'			=> 0
    	];

    	$examScheduleRepository->createDataExamScheduleStudent($data);
    	return SendResponse::accept('exam stored');
    }

    /**
     * Start student's exam
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Repositories\ExamScheduleRepository
     * @return \App\Actions\SendResponse
     */
    public function start(ExamScheduleRepository $examScheduleRepository)
    {
    	$student = request()->user('api');

    	$examScheduleRepository->startDataExamScheduleStudent($student->id);
    	return SendResponse::accept('exam started');
    } 

    /**
     * Get student's answer
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Repositories\ExamScheduleRepository
     * @return \App\Actions\SendResponse
     */
    public function indexAnswer(ExamStudentRepository $examStudentRepository, ExamScheduleRepository $examScheduleRepository, QuestionRepository $questionRepository)
    {
    	$student = request()->user('api');
    	$examScheduleRepository->uncompleteExamScheduleStudent($student->id);
    	$exam = $examScheduleRepository->getExamSchedule();
    	if(!$exam) {
    		return SendResponse::badRequest("We can't found your exam");
    	}

    	$examScheduleRepository->getDataExamSchedule($exam->exam_schedule_id);
    	$schedule = $examScheduleRepository->getExamSchedule();
    	if(!$schedule) {
    		return SendResponse::badRequest(" We can't found your schedule");
    	}

    	$student_answers = $examStudentRepository->getDataStudentAnswers($schedule->id, $student->id, $schedule->setting['random_option']);

    	if($student_answers->count() < 1) {
    		$questionRepository->getDataQuestionBank($schedule->question_bank_id);
    		$bank = $questionRepository->getQuestionBank();
    		$max_mc = $bank->mc_count;
    		$max_esay = $bank->esay_count;

    		$questionRepository->getDataQuestionsByType($bank->id, "1", $max_mc, $schedule->setting['random_question']);
    		$mc = $questionRepository->getQuestions();

    		$question_mc = $mc->map(function($item) use ($student, $bank, $schedule) {
    			return [
    				'question_bank_id' 	=> $bank->id,
    				'question_id' 		=> $item->id,
    				'student_id' 		=> $student->id,
    				'exam_schedule_id' 	=> $schedule->id,
    				'answer' 			=> 0,
    				'esay' 				=> '',
    				'doubt' 			=> 0,
    				'iscorrect' 		=> 0
    			];
    		});

    		$questionRepository->getDataQuestionsByType($bank->id, "2", $max_mc, $schedule->setting['random_question']);
    		$esay = $questionRepository->getQuestions();

    		$question_esay = $esay->map(function($item) use ($student, $bank, $schedule) {
    			return [
    				'question_bank_id' 	=> $bank->id,
    				'question_id' 		=> $item->id,
    				'student_id' 		=> $student->id,
    				'exam_schedule_id' 	=> $schedule->id,
    				'answer' 			=> 0,
    				'esay' 				=> '',
    				'doubt' 			=> 0,
    				'iscorrect' 		=> 0
    			];
    		});

    		$answers = [
    			$question_mc->values()->toArray(),
    			$question_esay->values()->toArray()
    		];

    		$examStudentRepository->createDataStudentAnswers($answers);

    		$student_answers = $examStudentRepository->getDataStudentAnswers($schedule->id, $student->id, $schedule->setting['random_option']);

    		return SendResponse::acceptCustom([
    			'data' => $student_answers,
    			'detail' => $exam
    		]);
    	}

    	$start = Carbon::createFromFormat('H:i:s', $exam->start);
    	$now = Carbon::createFromFormat('H:i:s', Carbon::now()->format('H:i:s'));
    	$diff_in_minutes = $start->diffInSeconds($now);

    	if($diff_in_minutes > $schedule->duration) {
            $exam->status = 1;
            $exam->save();

            $examStudentRepository->finishingExamStudent($schedule->id, $student->id);
        } else {
            $exam->remaining = $schedule->duration-$diff_in_minutes;
            $exam->save();
        }

        return SendResponse::acceptCustom([
    		'data' => $student_answers,
    		'detail' => $exam
    	]);
    }

    /**
     * Chagne student's answer
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Repositories\ExamRepository
     * @return \App\Actions\SendResponse
     */
    public function storeAnswer(Request $request, ExamStudentRepository $examStudentRepository)
    {
    	$examStudentRepository->updateDataStudentAnswer($request);
    	return SendResponse::acceptCustom([
    		'data'	=> $examStudentRepository->getStudentAnswer(),
    		'index'	=> $request->index
    	]);
    }

    /**
     * Change doubt student's answer
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ExamRepository
     * @return \App\Actions\SendResponse
     */
    public function doubtAnswer(Request $request, ExamStudentRepository $examStudentRepository)
    {
    	$examStudentRepository->updateDataStudentAnswerDoubt($request);
    	return SendResponse::acceptCustom([
    		'data'	=> $examStudentRepository->getStudentAnswer(),
    		'index'	=> $request->index
    	]);
    }

    /**
     * Finishing student's exam
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ExamRepository
     * @return \App\Actions\SendResponse
     */
    public function finishExam(ExamStudentRepository $examStudentRepository, ExamScheduleRepository $examScheduleRepository)
    {
    	$student = request()->user('api');

    	$examScheduleRepository->uncompleteExamScheduleStudent($student->id);
    	$exam = $examScheduleRepository->getExamSchedule();

    	if(!$exam) {
    		return SendResponse::accept('There no exam');
    	}

    	$examStudentRepository->finishingExamStudent($exam->exam_schedule_id, $student->id);
    	return SendResponse::accept('Exam finished');
    }
}
