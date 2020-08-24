<?php

namespace App\Http\Controllers\Api\v1;

use App\Exports\AbcentSubjectClassroomExport;
use App\Repositories\AbcentRepository;
use App\Http\Requests\AbcentRequest;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class AbcentController extends Controller
{
    /**
     * Get subject's classroom today
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $subject_id
     * @param int $classroom_id
     * @return \App\Actions\SendResponse
     */
    public function subjectClassroomToday($subject_id, $classroom_id, AbcentRepository $abcentRepository)
    {
    	$abcentRepository->getDataAbcentSubjectClassroomToday($subject_id, $classroom_id);
    	return SendResponse::acceptData($abcentRepository->getAbcents());
    }

    /**
     * Create new abcent for today 
     *
     * @author shellrean <wandinak17@gmail.com>
     * @return \App\Actions\SendResponse
     */
    public function store(AbcentRequest $request, AbcentRepository $abcentRepository)
    {
        if(!$request->user_id) {
            $user = request()->user('api');
            $request->user_id = $user->id;
        }
        $abcentRepository->createNewAbcent($request);
        return SendResponse::accept('abcent saved');
    }

    /**
     * Export data subject's classroom today
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $subject_id
     * @param int $classroom_id
     * @return \App\Actions\SendResponse
     */
    public function subjectClassroomTodayExport($subject_id, $classroom_id, AbcentRepository $abcentRepository) 
    {
        $abcentRepository->getDataAbcentSubjectClassroomToday($subject_id, $classroom_id);
        return Excel::download(new AbcentSubjectClassroomExport($abcentRepository->getAbcents()), 'abcent_subject_classroom.xlsx');
    }

    /**
     * Get data areport abcent today
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Reposiories\AbcentReposiory
     * @return \App\Actions\SendResponse
     */
    public function reportToday(AbcentRepository $abcentRepository)
    {
        $abcentRepository->getProblemToday();
        return SendResponse::acceptData($abcentRepository->getReports());
    }
}
