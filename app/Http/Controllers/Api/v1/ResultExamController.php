<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\ExamStudentRepository;
use App\Repositories\ResultRepository;
use App\Http\Requests\ExamResultStore;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;

class ResultExamController extends Controller
{
    /**
     * Check un checked esay data
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ResultRepository
     * @return \App\Actions\SendResponse
     */
    public function uncheckEsay($exam_schedule_id, ResultRepository $resultRepository)
    {
    	$resultRepository->getDataUncheckEsay($exam_schedule_id);
    	return SendResponse::acceptData($resultRepository->unchecks);
    }

    /**
     * Store esay correction
     *
     * @author shelrlean <wandinak17@gmail.com>
     * @param \App\Repositories\ResultRepository
     * @return \App\Actions\SendResonse
     */
    public function storeCheckEsay(ExamResultStore $request, ExamStudentRepository $examStudentRepository, ResultRepository $resultRepository)
    {
    	$examStudentRepository->getDataStudentAnswer($request->id);
    	$resultRepository->setPointStudentEsayAnswer($request, $examStudentRepository->getStudentAnswer());
    	return SendResponse::accept('esay check stored');
    }

    /**
     * Get data exam result
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\ResultRepository
     * @param $exam_schedule_id
     * @return \App\Actions\SendResponse
     */
    public function resultPoint($exam_schedule_id, ResultRepository $resultRepository)
    {
        $class_id = isset(request()->q) && request()->q != ''
                    ? request()->q
                    : '';
        $resultRepository->getDataResults($exam_schedule_id, $class_id);
        return SendResponse::acceptData($resultRepository->results);
    }
}
