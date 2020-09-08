<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\StandartRepository;
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
    	$per_page = isset(request()->perPage) && request()->perPage != ''
    				? request()->perPage
    				: 10;
    	$user = request()->user('api');
    	$standartRepostory->getDataStandarts($per_page, $user->id);
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
    	$standartRepostory->deleteDataStnadart($standart_id);
    	return SendResponse::accept('standart deleted');
    }
}
