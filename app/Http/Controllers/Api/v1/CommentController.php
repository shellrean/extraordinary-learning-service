<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\CommentRepository;
use App\Http\Requests\CommentRequest;
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
    public function index($lecture_id, CommentRepository $commentRepository)
    {
    	$perPage = isset(request()->perPage) && request()->perPage != '' 
    				? request()->perPage 
    				: 10;
        $type = isset(request()->type) && request()->type != '' 
                ? request()->type
                : 'lecture';
    	$commentRepository->getDataComments($lecture_id, $perPage, $type);
    	return SendResponse::acceptData($commentRepository->getComments());
    }

    /**
     * Create new lecture comment
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $lecture_id
     * @return \App\Actions\SendResponse
     */
    public function store($lecture_id, CommentRequest $request, CommentRepository $commentRepository)
    {
        $user = request()->user('api');
        $request->user_id = $user->id;
        $commentRepository->createNewComment($request);
        return SendResponse::acceptData($commentRepository->getComment());
    }

    /**
     * Deelte lecture comment
     *
     * @author shellrean <wandinak17@gmail.com>
     * @param int $comemnt_id
     * @return \App\Actions\SendResponse
     */
    public function destroy($comment_id, CommentRepository $commentRepository)
    {
    	$commentRepository->getDataComment($comment_id);
    	$commentRepository->deleteDataComment();
    	return SendResponse::accept('comment deleted');
    }
}
