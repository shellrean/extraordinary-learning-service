<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\PhotoRequest;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserImport;
use App\Actions\SendResponse;
use App\Services\FileService;
use Illuminate\Http\Request;
use App\Token;

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
    	$auth = request()->user('api');
        $user = \App\ClassroomStudent::with('classroom')->where('student_id', $auth->id)->first();
        if($user) {
            $auth->classroom = $user->classroom;
        }
        if($auth->role == '1') {
            if($auth->classroom()->exists()) {
                $auth->classroom->makeVisible('invitation_code');
            }
        }
        $token = Token::where('user_id', $auth->id)->first();
        if(!$token) {
            $token = Token::create([
                'user_id'   => $auth->id,
                'name'      => 'download_token',
                'token'     => bcrypt($auth->id)
            ]);
        } else {
            $token->token = bcrypt($auth->id);
            $token->save();
        }
        $auth->token_download = $token->token;
    	return SendResponse::acceptData($auth);
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
     * Get user student
     *
     * @author shellrean <wandinak17@gmail.com>
     * @return \App\Actions\SendResponse
     */
    public function indexStudent(UserRepository $userRepository)
    {
        $perPage = isset(request()->perPage) && request()->perPage != '' 
                    ? request()->perPage 
                    : 10;
        $search = isset(request()->q) ? request()->q : '';

        $userRepository->getDataUsers($perPage, $search, '2');
        return SendResponse::acceptData($userRepository->getUsers());
    }

    /**
     * Store new user teacher
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Requests\UserRequest $request
     * @return \App\Actions\SendResponse
     */
    public function storeTeacher(UserRequest $request, UserRepository $userRepository)
    {
        $request->role = '1';
        $request->isactive = true;

        $userRepository->createNew($request);
        return SendResponse::acceptData($userRepository->getUser());
    }

    /**
     * Store new user student
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Requests\UserRequest $request
     * @return \App\Actions\SendResponse
     */
    public function storeStudent(UserRequest $request, UserRepository $userRepository)
    {
        $request->role = '2';
        $request->isactive = true;

        $userRepository->createNew($request);
        return SendResponse::acceptData($userRepository->getUser());
    }

    /**
     * Get user's detail by id
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $id
     * @param \App\Repositories\UserRepository $userReqpository
     * @return \App\Actions\SendResponse
     */
    public function show($id, UserRepository $userRepository)
    {
        $userRepository->getDataUser($id);
        return SendResponse::acceptData($userRepository->getUser());
    }

    /**
     * Update user
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Requests\UserRequest $request
     * @return \App\Actions\SendResponse
     */
    public function update($id, UserUpdateRequest $request, UserRepository $userRepository)
    {
        $userRepository->getDataUser($id);
        $userRepository->updateDataUser($request);
        return SendResponse::acceptData($userRepository->getUser());
    }

    /**
     * Remove user from data
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $id
     * @param \App\Respositories\UserRepository $userRepository
     * @return \App\Actions\SendResponse
     */
    public function destroy($id, UserRepository $userRepository)
    {
        $userRepository->getDataUser($id);
        $delete = $userRepository->deleteDataUser();

        return SendResponse::accept('delete success');
    }

    /**
     * Change user photo
     * 
     * @author shellrean  <wandinak17@gmail.com>
     * @param \App\Http\Requests\PhotoRequest $request
     * @param \App\Respositories\UserRepository $userRepository
     * @return \App\Actions\SendResponse
     */
    public function updatePhoto(PhotoRequest $request, UserRepository $userRepository, FileService $fileService)
    {
        $user = request()->user('api');
        $store = $fileService->store($request);

        $userRepository->setUser($user);
        $userRepository->updatePhoto($fileService->fileDetail['filename']);

        return SendResponse::acceptData($fileService->fileDetail['filename']);
    }

    /**
     * Change user isonline status
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Request $request
     * @return \App\Actions\SendResponse
     */
    public function setOnlineUser(Request $request, UserRepository $userRepository) 
    {
        $user = request()->user('api');

        $userRepository->setUser($user);
        $userRepository->updateDataOnlineUser($request);

        return SendResponse::accept();
    }

    /**
     * Import teacher from excel file
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Request\UserImport
     * @return \App\Actions\SendResponse
     */
    public function importTeacher(UserImport $request, UserRepository $userRepository)
    {
        $userRepository->importTeacher($request);
        return SendResponse::accept('teacher imported');
    }

    /**
     * Import student from excel file
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \App\Http\Request\UserImport
     * @return \App\Actions\SendResponse
     */
    public function importStudent(UserImport $request, UserRepository $userRepository)
    {
        $userRepository->importStudent($request);
        return SendResponse::accept('student imported');
    }

    /**
     * Get user offline
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \Illuminate\Http\Request
     * @return \App\Actions\SendResponse
     */
    public function userOffline(Request $request, UserRepository $userRepository) 
    {
        $userRepository->getUserNotInData($request);
        return SendResponse::acceptData($userRepository->getUsers());
    }

    /**
     * Change user profile
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param \Illuminate\Http\Request
     * @return \App\Actions\SendResponse
     */
    public function updateProfile(Request $request, UserRepository $userRepository)
    {
        $user = request()->user('api');
        $userRepository->setUser($user);
        $userRepository->updateDataProfile($request);
        return SendResponse::acceptData($userRepository->getUser());
    }
}
