<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\PaperRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaperStore;
use App\Services\FileService;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class PaperController extends Controller
{
    /**
     * Get data papers
     * 
     * @param \App\Repositories\PaperRepository
     * @author shellrean <wandinak17@gmail.com>
     * @return \App\Actions\SendResponse 
     */
    public function index($classroom_subject_id, PaperRepository $paperRepository)
    {
        $teacher = request()->user('api');
        $type = isset(request()->type) && in_array(request()->type, ['syllabus','lesson_plan'])
                ? request()->type
                : '';
        $paperRepository->getDataPapers($type, $teacher->id, $classroom_subject_id);
        return SendResponse::acceptData($paperRepository->getPapers());
    }

    /**
     * Get data paper
     * 
     * @param \App\Repositories\PaperRepository
     * @author shellrean <wandinak17@gmail.com>
     * @param $paper_id
     * @return \App\Actions\SendResponse
     */
    public function show($paper_id, PaperRepository $paperRepository)
    {
        $paperRepository->getDataPaper($paper_id);
        return SendResponse::acceptData($paperRepository->getPaper());
    }

    /**
     * Create data paper
     * 
     * @param \App\Repositories\PaperRepository
     * @author shellraen <wandinak17@gmail.com>
     * @param \App\Http\Requests\PaperStore
     * @return \App\Actions\SendResponse
     */
    public function store(PaperStore $request, PaperRepository $paperRepository, FileService $fileService)
    {
        $fileService->store($request);
        $user = request()->user('api');

        $request->teacher_id = $user->id;
        $request->file_location = $fileService->fileDetail['filename'];
        $paperRepository->createDataPaper($request);
        return SendResponse::acceptData($paperRepository->getPaper());
    }

    /**
     * Update data paper
     * 
     * @param \App\Repositories\PaperRepository
     * @author shellrean <wandinak17@gmail.com>
     * @param $paper_id
     * @param \App\Http\Requests\PaperStore
     * @return \App\Actions\SendResponse
     */
    public function update($paper_id, PaperStore $request, PaperRepository $paperRepository, FileService $fileService)
    {
        if($request->hasFile('file')) {
            $fileService->store($request);
            $request->file_location = $fileService->fileDetail['filename'];
        }
        $paperRepository->updateDataPaper($request, $paper_id);
        return SendResponse::acceptData($paperRepository->getPaper());
    }

    /**
     * Delete data paper
     * 
     * @param \App\Repositories\PaperRepository
     * @author shellran <wandinak17@gmail.com>
     * @param $paper_id
     * @return \App\Actions\SendResponse
     */
    public function destroy($paper_id, PaperRepository $paperRepository, FileService $fileService)
    {
        $paperRepository->getDataPaper($paper_id);
        $fileService->remove('app/public/'.$paperRepository->getPaper()->file_location);
        
        $paperRepository->deleteDataPaper();
        return SendResponse::accept('paper deleted');
    }
}
