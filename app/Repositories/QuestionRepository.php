<?php

namespace App\Repositories;

use App\QuestionOption;
use App\QuestionBank;
use App\Question;

class QuestionRepository
{
	/**
	 * Data question_bank
	 * @var App\QuestionBank
	 */
	private $question_bank;

	/**
	 * Data question_banks
	 * @var Collection
	 */
	private $question_banks;

	/**
	 * Data question
	 * @var App\Question
	 */
	private $question;

	/**
	 * Data questions
	 * @var Collection
	 */
	private $questions;

	/**
	 * Retreive data question_bank
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return App\QuestionBank
	 */
	public function getQuestionBank()
	{
		return $this->question_bank;
	}

	/**
	 * Retreive data question_banks
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return Collection
	 */
	public function getQuestionBanks()
	{
		return $this->question_banks;
	}

	/**
	 * Retreive data question
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return App\Question
	 */
	public function getQuestion()
	{
		return $this->question;
	}

	/**
	 * Retreive data questions
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return Collection
	 */
	public function getQuestions()
	{
		return $this->questions;
	}

	/**
	 * Get data question bank
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $question_bank_id
	 * @return void
	 */
	public function getDataQuestionBank($question_bank_id, $exception = true)
	{
		try {
			$question_bank = QuestionBank::where('id', $question_bank_id)->first();
			if(!$question_bank && $exception) {
				throw new \App\Exceptions\ModelNotFoundException('question bank not found');
			}
			$this->question_bank = $question_bank;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data question banks
	 *
	 * @since shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $perPage
	 * @return void
	 */
	public function getDataQuestionBanks(int $per_page, $teacher_id = '')
	{
		try {
			$banks = QuestionBank::with('subject')->orderBy('id','desc');
			if($teacher_id != '') {
				$banks = $banks->where('author', $teacher_id);
			}
			$this->question_banks = $banks->paginate($per_page);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create new data question bank
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param App\Http\Request
	 * @return void
	 */
	public function createDataQuestionBank($request)
	{
		try {
			$data = [
				'code'	=> $request->code,
				'mc_count' => $request->mc_count,
				'mc_option_count' => $request->mc_option_count,
				'esay_count' => $request->esay_count,
				'percentage' => $request->percentage,
				'subject_id'	=> $request->subject_id,
				'author'	=> $request->author
			];
			$bank = QuestionBank::create($data);
			$this->question_bank = $bank;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data question bank
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param App\Http\Request
	 * @return void
	 */
	public function updateDataQuestionBank($question_bank_id, $request)
	{
		try {
			$this->getDataQuestionBank($question_bank_id);
			$data = [
				'code'	=> $request->code,
				'mc_count' => $request->mc_count,
				'mc_option_count' => $request->mc_option_count,
				'esay_count' => $request->esay_count,
				'percentage' => $request->percentage,
				'subject_id'	=> $request->subject_id,
				'author'	=> $request->author
			];
			$this->question_bank->update($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete data question bank
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $question_bank_id
	 * @return void
	 */
	public function deleteDataQuestionBank($question_bank_id)
	{
		try {
			QuestionBank::where('id', $question_bank_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}