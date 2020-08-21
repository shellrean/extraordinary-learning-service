<?php

namespace App\Repositories;

use App\StudentAnswer;
use App\QuestionBank;
use App\ExamSchedule;
use App\StudentExam;
use App\ExamResult;
use Illuminate\Support\Facades\DB;

class ExamStudentRepository
{
	/**
	 * Data student answers
	 * @var Collection
	 */
	private $student_answers;

	/**
	 * Data student answer
	 * @var StudentAsnwer
	 */
	private $student_answer;

	/**
	 * Retreive data student answer
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @return Collection
	 */
	public function getStudentAnswers()
	{
		return $this->student_answers;
	}

	/**
	 * Retreive data student answer
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @return DB;
	 */
	public function getStudentAnswer()
	{
		return $this->student_answer;
	}

	/**
	 * Get data student's answer
	 *
	 * @author shellrean <wandinak17@mgail.com>
	 * @param $schedule_id,
	 * @param $student_id
	 * @param $random_option
	 * @return void
	 */
	public function getDataStudentAnswers($schedule_id, $student_id, $random_option)
	{
		try {
			$data = StudentAnswer::with([
				'question'	=> function($query) {
					$query->select('id', 'question_bank_id', 'type','question', 'audio');
				},
				'question.options'	=> function ($query) use ($random_option) {
					$query->select('id' ,'question_id', 'body');
					if($random_option == "1") {
						$query->inRandomOrder();
					}
				}
			])
			->where([
				'student_id'		=> $student_id,
				'exam_schedule_id'	=> $schedule_id
			])
			->select('id', 'question_bank_id', 'question_id', 'answer', 'esay', 'doubt')
			->get();

			$this->student_answers = $data;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create data student's answers
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @param $data
	 * @return void
	 */
	public function createDataStudentAnswers($data)
	{
		DB::beginTransaction();
		try {
			DB::table('student_answers')->insert($data);
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data student's answer
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @param $request
	 * @return void
	 */
	public function updateDataStudentAnswer($request): void
	{
		try {
			$student_answer = StudentAnswer::find($request->answer_id);

			if(isset($request->esay)) {
				$student_answer->esay = $request->esay;
				$student_answer->save();

				$this->student_answer = $student_answer->only(['esay','answer']);
				return;
			}

			$ca = DB::table('question_options')->where('id', $request->answer)->first();
			if(!$ca) {
				$this->student_answer = $student_answer->only(['esay','answer']);
				return;
			}
			$student_answer->answer = $request->answer;
	        $student_answer->iscorrect = $ca->correct;
	        $student_answer->save();

	        $this->student_answer = $student_answer->only(['esay','answer']);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data student's answer doubt
	 * 
	 * @author shellrean <wandinak17@gamil.com>
	 * @param $request
	 * @return void
	 */
	public function updateDataStudentAnswerDoubt($request): void
	{
		try {
			$student_answer = StudentAnswer::find($request->answer_id);
			
			if(!isset($request->doubt)) {
	            $this->student_answer = $student_answer->only(['doubt']);
	            return;
	        }

	        $student_answer->doubt = $request->doubt;
	        $student_answer->save();

	        $this->student_answer = $student_answer->only(['doubt']);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update student exam ststus
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @param $student_id
	 * @param schedule_id
	 */
	public function stopStudentExam($student_id, $schedule_id)
	{
		try {
			$exam = StudentExam::where([
				'student_id'	=> $student_id,
				'exam_schedule_id' => $schedule_id
			])->first();

			$exam->status = 1;
			$exam->save();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Finishing data Student 
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @param $schedule_id
	 * @param $student_id
	 * @return void
	 */
	public function finishingExamStudent($schedule_id, $student_id)
	{
		DB::beginTransaction();
		try {
			$schedule = ExamSchedule::find($schedule_id);
			if(!$schedule) {
				throw new \App\Exceptions\ModelNotFoundException('Schedule not found');
			}
			$bank = QuestionBank::find($schedule->question_bank_id);
			if(!$bank) {
				throw new \App\Exceptions\ModelNotFoundException('Question bank not found');
			}
			
			$mc_correct = StudentAnswer::where([
				'iscorrect'	=> 1,
				'exam_schedule_id' => $schedule_id,
				'student_id' => $student_id
			])
			->whereHas('question', function($query) {
				$query->where('type','1');
			})
			->count();

			$mc_wrong = StudentAnswer::where([
				'iscorrect'	=> 0,
				'exam_schedule_id' => $schedule_id,
				'student_id' => $student_id
			])
			->whereHas('question', function($query) {
				$query->where('type','1');
			})
			->count();

			$mc_total = StudentAnswer::where([
				'exam_schedule_id' => $schedule_id,
				'student_id' => $student_id
			])
			->whereHas('question', function($query) {
				$query->where('type','1');
			})
			->count();

			$mc_result = 0;
            if($mc_total > 0 && $mc_correct > 0) {
                $mc_result = ($mc_correct/$mc_total)*$bank->percentage['mc'];
            }

            $null = StudentAnswer::where([
                'answer'     => 0,
                'exam_schedule_id'     => $schedule_id, 
                'student_id'    => $student_id,
            ])
            ->whereHas('question', function($query) {
                $query->where('type','1');
            })
            ->count();

            ExamResult::create([
            	'exam_schedule_id'	=> $schedule_id,
            	'student_id'		=> $student_id,
            	'wrong_mc'			=> $mc_wrong,
            	'correct_mc' 		=> $mc_correct,
            	'point_esay' 		=> 0,
            	'null' 				=> $null,
            	'result' 			=> $mc_result
            ]);

            $this->stopStudentExam($student_id, $schedule_id);
            DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}