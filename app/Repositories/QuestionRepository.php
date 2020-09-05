<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
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
			$question_bank = QuestionBank::with('subject')->where('id', $question_bank_id)->first();
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
				'subject_id'	=> $request->subject_id
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

	/**
	 * Get data questions
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $question_bank_id
	 * @return void
	 */
	public function getDataQuestions($question_bank_id, int $per_page)
	{
		try {
			$questions = Question::with('options')->where('question_bank_id', $question_bank_id);
			$this->questions = $questions->paginate($per_page);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data question type
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $question_bank_id,
	 * @param $type
	 * @return $void
	 */
	public function getDataQuestionsByType($question_bank_id, $type, $max, $random)
	{
		try {
			$questions = DB::table('questions')->where([
				'question_bank_id'	=> $question_bank_id,
				'type'	=> $type
			]);
			if($random == "1") {
				$questions = $questions->inRandomOrder();
			}
			$questions = $questions->take($max)->get();

			$this->questions = $questions;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create data question
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \App\Http\Requests\QuestionStore
	 * @return void
	 */
	public function createDataQuestion($request)
	{
		DB::beginTransaction();
		try {
			$data = [
				'question_bank_id'	=> $request->question_bank_id,
				'type'	=> $request->type,
				'question'	=> $request->question,
				'audio'	=> $request->audio
			];
			$question = Question::create($data);

			if($request->type == 1) {
				$options = [];
				foreach($request->options as $key => $option) {
					array_push($options, [
						'question_id'	=> $question->id,
						'body'	=> $option,
						'correct' => ($request->correct == $key ? 1 : 0 )
					]);
				}
				DB::table('question_options')->insert($options);
			}

			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data question
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $question_id
	 * @return void
	 */
	public function getDataQuestion($question_id)
	{
		try {
			$question = Question::with('options')->where('id', $question_id)->first();
			$this->question = $question;
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data question
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $question_id
	 * @param \App\Http\Requests\QuestionStore
	 * @return void
	 */
	public function updateDataQuestion($question_id, $request)
	{
		DB::beginTransaction();
		try {
			$question = Question::find($question_id);
			$data = [
				'type'	=> $request->type,
				'question'	=> $request->question,
				'audio'	=> $request->audio
			];
			$question->update($data);
			if($request->type == 1) {
				DB::table('question_options')->where('question_id', $question_id)->delete();
				$options = [];
				foreach($request->options as $key => $option) {
					array_push($options, [
						'question_id'	=> $question->id,
						'body'	=> $option,
						'correct' => ($request->correct == $key ? 1 : 0 )
					]);
				}
				DB::table('question_options')->insert($options);
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete data question
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $question_id
	 * @return void
	 */
	public function deleteDataQuestion($question_id)
	{
		try {
			DB::table('questions')->where('id', $question_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Import from array object docs
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param $question_bank_id
	 * @param $question
	 * @return void
	 */
	public function importQues($question, $question_bank_id)
	{
		foreach($question as $key => $singlequestion){
			if($key != 0){
				$question = $singlequestion['question'];
				$question= str_replace("`",'&#39;',$question);
		        $question= str_replace("‘",'&#39;',$question);
		        $question= str_replace("’",'&#39;',$question);
		        $question= str_replace("â€œ",'&#34;',$question);
		        $question= str_replace("â€˜",'&#39;',$question);

		        $question= str_replace("â€™",'&#39;',$question);
		        $question= str_replace("â€",'&#34;',$question);
		        $question= str_replace("'","&#39;",$question);
		        $question= str_replace("\n","<br>",$question);

		        $option_count=count($singlequestion['option']);
		        $ques_type="0";
		        if($option_count!="0"){
		         	if($singlequestion['correct']!=""){
		            	if (strpos($singlequestion['correct'],',') !== false) {
		              		$ques_type="1";
		            	}else{
		              		$ques_type="0";
		            	}
		          	}else{
		            }
		        }else{
		        }
		        if($ques_type==0){
				  $ques_type2=1;
				}
				if($ques_type==1){
					$ques_type2=2;
				}
				$corect_position=array(
					'A' => '0',
					'B' => '1',
					'C' => '2',
					'D' => '3',
					'E' => '4',
					'F' => '5',
					'G' => '6',
					'H' => '7'
				);

				$insert_data = array(
					'question_bank_id' => $question_bank_id,
					'type'   => $ques_type2,
					'question' => $question
				);

				DB::beginTransaction();

				try {
					$question = Question::create($insert_data);

					if($ques_type=="0" || $ques_type=="1"){
						$correct_op=array_filter(explode(',',$singlequestion['correct']));
						$correct_option_position=array();
						foreach($correct_op as $v){
							$correct_option_position[]=$corect_position[trim($v)];
						}

						$options = [];
						foreach($singlequestion['option'] as $corect_key => $correct_val){
							if(in_array($corect_key, $correct_option_position)){
								$divideratio=count($correct_option_position);
								$correctoption =1;
							} else {
								$correctoption =0;
							}

							$array = [
								'question_id' => $question->id,
								'body' => $correct_val,
								'correct' => $correctoption
							];

							array_push($options, $array);
						}
						DB::table('question_options')->insert($options);
					}

					DB::commit();
				} catch (\Exception $e) {
					DB::rollback();
					throw new \App\Exceptions\ModelException($e->getMessage());
				}
			}
		}
	}
}