<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\User;
use Auth;

class AuthController extends Controller
{
    /**
     * Login to Api
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \ILluminate\Http\Request $request
     * @return \App\Actions\SendResponse
     **/
    public function login(LoginRequest $request)
    {
        $credentials = request(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token =  $user->createToken('Personal Access Token')->accessToken;
            return SendResponse::acceptCustom([
                'status' => 'success',
                'token' => $token
            ]);
        }
        return SendResponse::acceptData('invalid-credentials');
    }

    /**
     * Logout from Api and revoke token
     *
     * @author shellrean <wandinak17@gmail.com>
     * @return \App\Actions\SendResponse
     */
    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();

        return SendResponse::accept();
    }
}
