<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\AbcentRepository;
use App\Exports\AbcentScheduleExport;
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
    public function scheduleClassroomToday($schedule_id, AbcentRepository $abcentRepository)
    {
        $date = isset(request()->date) && request()->date
                ? request()->date
                : '';
        if($date != '') {
            $date = \Carbon\Carbon::parse($date);
        }
        $abcentRepository->getDataAbcentScheduleDay($schedule_id, $date);
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
    public function scheduleClassroomTodayExport($schedule_id, AbcentRepository $abcentRepository) 
    {
        $date = isset(request()->date) && request()->date
                ? request()->date
                : '';
        if($date != '') {
            $date = \Carbon\Carbon::parse($date);
        }
        $abcentRepository->getDataAbcentScheduleDay($schedule_id, $date);
        return Excel::download(new AbcentScheduleExport($abcentRepository->getAbcents()), 'abcent_subject_classroom.xlsx');
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
        $date = isset(request()->q) && request()->q
                ? request()->q
                : '';
        if($date != '') {
            $date = \Carbon\Carbon::parse($date);
        }
        $abcentRepository->getProblemToday($date);
        return SendResponse::acceptData($abcentRepository->getReports());
    }
}
