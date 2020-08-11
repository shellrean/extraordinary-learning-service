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
    public function changeToChannel($channel_id, SocketService $socketService)
    {
    	$user = request()->user('api');
    	$socketService->setUserToChannel($user->id, $channel_id);
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
