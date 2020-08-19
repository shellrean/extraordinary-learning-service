<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\QuestionBankStore;
use App\Repositories\QuestionRepository;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Get data question banks
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositores\QuestionRepository
     * @return \App\Actions\SendResponse
     */
    public function index(QuestionRepository $questionRepository)
    {
    	$per_page = isset(request()->perPage) && request()->perPage != ''
    				? request()->perPage
    				: 10;
    	$user = request()->user('api');
    	$questionRepository->getDataQuestionBanks($per_page, $user->id);
    	return SendResponse::acceptData($questionRepository->getQuestionBanks());
    }

    /**
     * Create new data question bank
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Requests\QuestionBankStore
     * @param \App\Repositories\QuestionRepository
     * @return \App\Actions\SendResponse
     */
    public function store(QuestionBankStore $request, QuestionRepository $questionRepository)
    {
    	$user = request()->user('api');
    	$request->author = $user->id;
    	$questionRepository->createDataQuestionBank($request);
    	return SendResponse::acceptData($questionRepository->getQuestionBank());
    }

    /**
     * Get data question bank
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\QuestionRepository
     * @return \App\Actions\SendResponse
     */
    public function show($question_bank_id, QuestionRepository $questionRepository)
    {
        $questionRepository->getDataQuestionBank($question_bank_id);
        return SendResponse::acceptData($questionRepository->getQuestionBank());
    }

    /**
     * Update data question bank
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Requests\QuetionBankStore
     * @param \App\Repositories\QuestionRepository
     * @return \App\Actions\SendResponse
     */
    public function update($question_bank_id, QuestionBankStore $request,QuestionRepository $questionRepository)
    {
    	$questionRepository->updateDataQuestionBank($question_bank_id, $request);
    	return SendResponse::acceptData($questionRepository->getQuestionBank());
    }

    /**
     * Delete data question bank
     *
  	 * @author shellrean <wandinak17@gmail.com>
  	 * @param \App\Repositories\QuestionRepository
  	 * @return \App\Actions\SendResponse
  	 */
    public function destroy($question_bank_id, QuestionRepository $questionRepository)
    {
    	$questionRepository->deleteDataQuestionBank($question_bank_id);
    	return SendResponse::accept('question bank deleted');
    }
}
