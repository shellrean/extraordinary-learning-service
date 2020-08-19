<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    /**
     * Upload file
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Request
     * @return \App\Http\Response
     */
    public function store(Request $request, FileService $fileService)
    {
    	if ($request->hasFile('upload')) {
    		$fileService->store($request, 'upload');

    		$url = asset('storage/'.$fileService->fileDetail['filename']);
    		return SendResponse::acceptCustom([
    			'uploaded' => 1,
                'fileName' => $fileService->fileDetail['filename'],
                'url' => $url
    		]); 
    	}
    }
}
