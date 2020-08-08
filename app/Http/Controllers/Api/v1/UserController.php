<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Get current user login
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @return \App\Actions\SendResponse
     */
    public function getUserLogin()
    {
    	$user = request()->user('api');
    	return SendResponse::acceptData($user);
    }
}
