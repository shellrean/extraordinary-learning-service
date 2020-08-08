<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
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

    /**
     * Get user teacher
     *
     * @author shellrean <wandinak17@gmail.com>
     * @return \App\Actions\SendResponse
     */
    public function indexTeacher(UserRepository $userRepository)
    {
        $perPage = isset(request()->perPage) && request()->perPage != '' 
                    ? request()->perPage 
                    : 10;
        $search = isset(request()->q) ? request()->q : '';

        $userRepository->getDataUsers($perPage, $search);

        return SendResponse::acceptData($userRepository->getUsers());
    }

    /**
     * Store new user
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Requests\UserRequest $request
     * @return \App\Actions\SendResponse
     */
    public function storeTeacher(UserRequest $request, UserRepository $userRepository)
    {
        $request->role = '1';
        $created = $userRepository->createNew($request);
        if($created['error']) {
            return SendResponse::serverError($created['message']);
        }
        return SendResponse::acceptData($created['data']);
    }
}
