<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\AbcentRepository;
use App\Http\Requests\AbcentRequest;
use App\Http\Controllers\Controller;
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
     * @param int $subject_id
     * @param int $classroom_id
     * @return \App\Actions\SendResponse
     */
    public function store($subject_id, $classroom_id, AbcentRequest $request, AbcentRepository $abcentRepository)
    {
        if(!$request->user_id) {
            $user = request()->user('api');
            $request->user_id = $user->id;
        }
        $abcentRepository->createNewAbcent($request);
        return SendResponse::accept('abcent saved');
    }
}
