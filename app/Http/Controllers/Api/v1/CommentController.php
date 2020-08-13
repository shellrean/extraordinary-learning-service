<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\CommentRepository;
use App\Http\Requests\LectureComment;
use App\Http\Controllers\Controller;
use App\Actions\SendResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Get lecture comments
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $lecture_id
     * @return \App\Actions\SendResponse
     */
    public function indexLecture($lecture_id, CommentRepository $commentRepository)
    {
    	$perPage = isset(request()->perPage) && request()->perPage != '' 
    				? request()->perPage 
    				: 10;
    	$commentRepository->getDataLectureComments($lecture_id, $perPage);
    	return SendResponse::acceptData($commentRepository->getLectureComments());
    }

    /**
     * Create new lecture comment
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $lecture_id
     * @return \App\Actions\SendResponse
     */
    public function storeLecture($lecture_id, LectureComment $request, CommentRepository $commentRepository)
    {
        $user = request()->user('api');
        $request->user_id = $user->id;
        $commentRepository->createNewLectureComment($request);
        return SendResponse::acceptData($commentRepository->getLectureComment());
    }

    /**
     * Deelte lecture comment
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $comemnt_id
     * @return \App\Actions\SendResponse
     */
    public function destroyLecture($comment_id, CommentRepository $commentRepository)
    {
    	$commentRepository->getDataLectureComment($comment_id);
    	$commentRepository->deleteDataLectureComment();
    	return SendResponse::accept('comment deleted');
    }

    /**
     * Get classroom live comments
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $classroom live id
     * @return \App\Actions\SendResponse
     */
    public function indexClassroomLive($id, CommentRepository $commentRepository)
    {
        $perPage = isset(request()->perPage) && request()->perPage != '' 
                    ? request()->perPage 
                    : 10;
        $commentRepository->getDataClassroomLiveComments($id, $perPage);
        return SendResponse::acceptData($commentRepository->getClassroomLiveComments());
    }

    /**
     * Create new classroom live comment
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $lecture_id
     * @return \App\Actions\SendResponse
     */
    public function storeClassroomLive($lecture_id, ClassroomLiveComment $request, CommentRepository $commentRepository)
    {
        $user = request()->user('api');
        $request->user_id = $user->id;
        $commentRepository->createNewClassroomLiveComment($request);
        return SendResponse::acceptData($commentRepository->getClassroomLiveComment());
    }
}
