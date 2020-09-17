<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\QuestionBankStore;
use App\Repositories\QuestionRepository;
use App\Http\Requests\QuestionImport;
use App\Http\Requests\QuestionStore;
use App\Http\Controllers\Controller;
use App\Services\WordService;
use App\Services\FileService;
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

    /**
     * Import question from docx
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\QuestionRepository
     * @return \App\Actions\SendResponse
     */
    public function import($question_bank_id, QuestionImport $request, WordService $wordService, FileService $fileService, QuestionRepository $questionRepository)
    {
        $fileService->store($request);

        $path = storage_path('app/'.$fileService->fileDetail['path']);
        $read = $wordService->wordFileImport($path);
        if(!$read) {
            return SendResponse::badRequest("Can't read file doc");
        }
        $questionRepository->importQues($read, $question_bank_id);

        return SendResponse::accept('question imported');
    }

    /**
     * Get data question_bank's questions
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\QuestionRepository
     * @param $question_bank_id
     * @return \App\Actions\SendResponse
     */
    public function indexQuestion($question_bank_id, QuestionRepository $questionRepository) 
    {
        $per_page = isset(request()->perPage) && request()->perPage != ''
                    ? request()->perPage
                    : 10;
        $questionRepository->getDataQuestions($question_bank_id, $per_page);
        return SendResponse::acceptData($questionRepository->getQuestions());
    }

    /**
     * Store data question_bank's question
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\QuestionRepository
     * @param \App\Requests\QuestionStore
     * @return \App\Actions\SendResponse
     */
    public function storeQuestion(QuestionStore $request, QuestionRepository $questionRepository)
    {
        $questionRepository->createDataQuestion($request);
        return SendResponse::acceptData('question created');
    }

    /**
     * Get data question
     *
     * @author shellran <wandinak17@gmail.com>
     * @param \App\Repositories\QuestionRepository
     * @param $question_id
     * @return \App\Actions\SendResponse
     */
    public function showQuestion($question_id, QuestionRepository $questionRepository)
    {
        $questionRepository->getDataQuestion($question_id);
        return SendResponse::acceptData($questionRepository->getQuestion());
    }

    /**
     * Update data question
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\QuestionRepository
     * @param $question_id
     * @param \App\Http\Requests\QuestionStore
     * @return \App\Actions\SendResponse
     */
    public function updateQuestion($question_id, QuestionStore $request, QuestionRepository $questionRepository)
    {
        $questionRepository->updateDataQuestion($question_id, $request);
        return SendResponse::acceptData('question updated');
    }

    /** 
     * Destroy data question
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\QuestionRepository
     * @param $question_id
     * @param \App\Http\Requests\QuestionStore
     * @return \App\Actions\SendResponse
     */
    public function destroyQuestion($question_id, QuestionRepository $questionRepository)
    {
        $questionRepository->deleteDataQuestion($question_id);
        return SendResponse::accept('question deleted');
    }

    /**
     * Duplicate data question bank
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\QuestionRepository
     * @param $question_bank_id
     * @return \App\Actions\SendResponse
     */
    public function duplicateQuestionBank($question_bank_id, QuestionRepository $questionRepository)
    {
        return $questionRepository->duplicateDataQuestionBank($question_bank_id);
        // return SendResponse::accept('question bank duplicated');
    }
}
