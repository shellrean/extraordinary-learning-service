<?php

namespace App\Http\Controllers\Api\v1;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Repositories\StandartRepository;
use App\Exports\StandartExportSpreet;
use App\Http\Controllers\Controller;
use App\Http\Requests\StandartStore;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class StandartController extends Controller
{
    /**
     * Get data standarts
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\StandartRepository
     * @return \App\Actions\SendResponse
     */
    public function index(StandartRepository $standartRepostory)
    {
        $user = request()->user('api');
        $subject_id = isset(request()->s) ? request()->s : '';

    	$standartRepostory->getDataStandarts($user->id, $subject_id);
    	return SendResponse::acceptData($standartRepostory->getStandarts());
    }

    /**
     * Get data standart
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Repositories\StandartRepository
     * @param $standart_id
     * @return \App\Actions\SendResponse
     */
    public function show($standart_id, StandartRepository $standartRepostory)
    {
    	$standartRepostory->getDataStandart($standart_id);
    	return SendResponse::acceptData($standartRepostory->getStandart());
    }

    /**
     * Create new data standart
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\Standartrepository
     * @param \App\Http\Requests\StandartStore
     * @return \App\Actions\SendResponse
     */
    public function store(StandartStore $request, StandartRepository $standartRepostory)
    {
    	$user = request()->user('api');
    	$request->teacher_id = $user->id;
    	$standartRepostory->createDataStandart($request);
    	return SendResponse::acceptData($standartRepostory->getStandart());
    }

    /**
     * Update data standart
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Repositories\StandartRepository
     * @param \App\Http\Requests\StandartStore
     * @param $standart_id
     * @return \App\Actions\SendResponse
     */
    public function update($standart_id, StandartStore $request, StandartRepository $standartRepostory)
    {
    	$standartRepostory->updateDataStandart($request, $standart_id);
    	return SendResponse::accept('standart updated');
    }

    /**
     * Delete data standart
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Repositories\StandartRepository
     * @param $standart_id
     * @return \App\Actions\SendResponse
     */
    public function destroy($standart_id, StandartRepository $standartRepostory)
    {
    	$standartRepostory->deleteDataStandart($standart_id);
    	return SendResponse::accept('standart deleted');
    }

    /**
     * Export data standart
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\StandartRepository
     * @return \App\Actions\SendResponse
     */
    public function exportExcel(StandartRepository $standartRepository)
    {
        $user_id = isset(request()->u) ? request()->u : '';
        $subject_id = isset(request()->s) ? request()->s : '';

        $subject = \App\Subject::find($subject_id);
        $user = \App\User::find($user_id);
        if(!$subject) {
            return SendResponse::notFound('Subject not found');
        }
        if(!$user) {
            return SendResponse::notFound('User not found');
        }

        $standartRepository->getDataStandarts($user->Id, $subject->id);

        $spreadsheet = StandartExportSpreet::export($standartRepository->getStandarts(), $subject->name);
        $writer = new Xlsx($spreadsheet);

        $filename = $subject->name.'_'.$user->name;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
        $writer->save('php://output');
    }
}
