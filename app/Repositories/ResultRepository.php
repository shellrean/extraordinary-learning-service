<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\StudentAnswer;
use App\EsayAnswer;
use App\ExamResult;

class ResultRepository
{
	/**
	 * Data uncheck data
	 * @var Collection
	 */
	public $unchecks;

	/**
	 * Data results data
	 * @var Collection
	 */
	public $results;

	/**
	 * Get uncheck data esay answer student
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $exam_schedule_id
	 * @return void
	 */
	public function getDataUncheckEsay($schedule_id)
	{
		try {
			$has = DB::table('esay_answers')->where([
				'exam_schedule_id'	=> $schedule_id,
			])->select('id','answer_id')->get()->pluck('answer_id');

			$exists = StudentAnswer::where( function($query) use ($has, $schedule_id) {
				$query->whereNotIn('id', $has)
				->whereHas('question', function($query) {
					$query->where('type','2');
				})
				->whereNotNull('esay')
				->where('exam_schedule_id', $schedule_id);
			})
			->with(['question' => function($query) {
				$query->select('id','question');
			}])
			->get();
			$this->unchecks = $exists;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Set point to data student esay answer
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.
	 * @param $request
	 * @return void
	 */
	public function setPointStudentEsayAnswer($request, $answer)
	{
		try {
			$result = ExamResult::where([
				'exam_schedule_id'	=> $answer->exam_schedule_id,
				'student_id'		=> $answer->student_id
			])->first();

			$mc_count = $answer->question_bank->mc_count;
			$esay_count = $answer->question_bank->esay_count;

			$result_mc = 0;
			if($result->correct_mc > 0) {
				$result_mc = ($result->correct_mc/$mc_count)*$answer->question_bank->percentage['mc'];
			}

			if($request->val != 0) {
				$result_esay = $result->point_esay + ($request->val/$esay_count);
			} else {
				$result_esay = $result->point_esay;
			}

			$result_point = ($result_mc) + ($result_esay*$answer->question_bank->percentage['esay']);
			$result->point_esay = $result_esay;
			$result->result = $result_point;
			$result->save();

			DB::table('esay_answers')->insert([
				[
					'exam_schedule_id'	=> $answer->exam_schedule_id,
					'student_id'	=> $answer->student_id,
					'answer_id'	=> $answer->id,
					'corrected_by' => request()->user('api')->id,
					'point' => $request->val
				]
			]);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data result exam schedule
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $exam_schedule_id
	 * @param $classroom_id
	 * @return void
	 */
	public function getDataResults($exam_schedule_id, $classroom_id = '')
	{
		try {
			$results = ExamResult::with(['student' => function($query) {
				$query->select('id','name');
			}])->orderBy('student_id');
			if($classroom_id != '') {
				$classroom_students = DB::table('classroom_students')->where('classroom_id', $classroom_id)->get()->pluck('student_id');
				$results = $results->whereIn('student_id', $classroom_students->toArray());
			}
			$this->results = $results->get();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}