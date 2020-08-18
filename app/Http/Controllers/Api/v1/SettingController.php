<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\SettingRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingStore;
use App\Actions\SendResponse;
use App\Services\FileService;

class SettingController extends Controller
{
    /**
     * Get setting by name
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Repositories\SettingRepository
     * @return \App\Actions\SendResponse
     */
    public function show($setting_name, SettingRepository $settingRepository)
    {
    	$settingRepository->getDataSetting($setting_name);
        $data = $settingRepository->getSetting();
        if(!$data) {
            $data = [
                'name'  => $setting_name
            ];
        }
    	return SendResponse::acceptData($data);
    }

    /**
     * Store setting 
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Htpp\Requests\SettingStore
     * @return \App\Actions\SendResponse
     */
    public function store(SettingStore $request, SettingRepository $settingRepository)
    {
        $settingRepository->createOrUpdateSetting($request);
        return SendResponse::accept('setting changed');
    }

    /**
     * Upload file image
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Htpp\Requests\SettingStore
     * @return \App\Actions\SendResponse
     */
    public function storeImage(SettingStore $request, SettingRepository $settingRepository, FileService $fileService)
    {
        $fileService->store($request);
        $request->merge([ 'settings' => [
            'logo' => $fileService->fileDetail['filename']
        ]]);
        $settingRepository->createOrUpdateSetting($request);
        return SendResponse::acceptData($settingRepository->getSetting());
    }
}
