<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\ChannelRequest;
use App\Http\Controllers\Controller;
use App\Services\SocketService;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    /**
     * Put user to channel
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param $channel_id
     * @param \App\Services\SocketService
     * @return \App\Actions\SendResponse
     */
    public function changeToChannel($channel_id, Request $request, SocketService $socketService)
    {
        $user_id = $request->user_id;
        if(!$request->user_id) {
    	   $user_id = request()->user('api')->id;
        }
        if($channel_id == 0) {
            $channel_id = '';
        }
    	$socketService->setUserToChannel($user_id, $channel_id);
    	return SendResponse::accept('channel changed');
    }

    /**
     * Get user in channel
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param $channel_id
     * @param \App\Services\SocketService
     * @return \App\Actions\SendResponse
     */
    public function getDataChannelUser($channel_id, SocketService $socketService)
    {
    	$data = $socketService->getDataUserOnChanel($channel_id);
    	return SendResponse::acceptData($data);
    }
}
