<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\SubjectRepository;
use App\Http\Requests\SubjectRequest;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Get data subjects
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param \App\Repositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function index(SubjectRepository $subjectRepository)
    {
    	$perPage = isset(request()->perPage) && request()->perPage == ''
    				? request()->perPage
    				: 10;
    	$search = isset(request()->q) ? request()->q : '';
    	$subjectRepository->getDataSubjects($perPage, $search);
    	return SendResponse::acceptData($subjectRepository->getSubjects());
    }

    /**
     * Create new subject
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param \App\Http\Requests\SubjectRequest $request
     * @param \App\Repositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function store(SubjectRequest $request, SubjectRepository $subjectRepository)
    {
    	$subjectRepository->createNewSubject($request);
    	return SendResponse::acceptData($subjectRepository->getSubject());
    }

    /**
     * Get data subject
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param $subject_id
     * @param \App\Repositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function show($subject_id, SubjectRepository $subjectRepository)
    {
    	$subjectRepository->getDataSubject($subject_id);
    	return SendResponse::acceptData($subjectRepository->getSubject());
    }

    /**
     * Update data subject
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param $subject_id 
     * @param \App\Http\Requests\SubjectRequest $request
     * @param \App\Repositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function update($subject_id, SubjectRequest $request, SubjectRepository $subjectRepository)
    {
    	$subjectRepository->updateDataSubject($request, $subject_id);
    	return SendResponse::acceptData($subjectRepository->getSubject());
    }

    /**
     * Remove data subject
     *
     * @author shellrean <wanddinak17@gmail.com>
     * @param $subject_id
     * @param \App\Reqpositories\SubjectRepository $subjectRepository
     * @return \App\Actions\SendResponse
     */
    public function destroy($subject_id, SubjectRepository $subjectRepository)
    {
    	$subjectRepository->deleteDataSubject($subject_id);
    	return SendResponse::accept('subject deleted');
    }
}
