<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\InfoRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\InfoStore;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    /**
     * Get data infos 
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\InfoRepository
     * @return \App\Actions\SendResponse
     */
    public function index(InfoRepository $infoRepository)
    {
    	$per_page = isset(request()->perPage) && request()->perPage != ''
    				? request()->perPage
    				: 10;
    	$infoRepository->getDataInfos($per_page);
    	return SendResponse::acceptData($infoRepository->getInfos());
    }

    /**
     * get data infos public
	 * 
	 * @author shellrean <wandinak17@gamil.com>
	 * @param \App\Repositories\InfoRepository
	 * @return \App\Actions\SendReponse
	 */
    public function public_info(InfoRepository $infoRepository)
    {
    	$per_page = isset(request()->perPage) && request()->perPage != ''
    				? request()->perPage
    				: 10;
    	$infoRepository->getDataInfos($per_page, '1');
    	return SendResponse::acceptData($infoRepository->getInfos());
    }

    /**
     * Get data info
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\InfoRepository
     * @return \App\Actions\SendResponse
     */
    public function show($info_id, InfoRepository $infoRepository)
    {
    	$infoRepository->getDataInfo($info_id);
    	return SendResponse::acceptData($infoRepository->getInfo());
    }

    /**
     * Create data info
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Repositories\InfoRepository
     * @param \App\Http\Requests\InfoStore
     * @return \App\Actions\SendResponse
     */
    public function store(InfoStore $request, InfoRepository $infoRepository)
    {
        $user = request()->user('api');
        $request->user_id = $user->id;
    	$infoRepository->createNewInfo($request);
        try { 
            \Telegram::sendMessage([
                'chat_id' => '-433413160', 
                'text' => $infoRepository->getInfo
            ]);
        } catch (\Exception $e) {
        }
    	return SendResponse::acceptData($infoRepository->getInfo());
    }

    /**
     * Update data info
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Repositories\InfoRepository
     * @param $info_id
     * @param \App\Http\Reqeusts\InfoStore
     * @return \App\Actions\SendResponse
     */
    public function update($info_id, InfoStore $request, InfoRepository $infoRepository)
    {
    	$infoRepository->updateDataInfo($request, $info_id);
    	return SendResponse::accept('info updated');
    }

    /**
     * Delete data info
     *
     * @author shellrean <wandinak17@gamil.com>
     * @param \App\Repositories\InfoRepository
     * @param $info_id
  	 * @return \App\Actions\SendResponse
  	 */
    public function destroy($info_id, InfoRepository $infoRepository)
    {
    	$infoRepository->deleteDataInfo($info_id);
    	return SendResponse::accept('info deleted');
    }
}
