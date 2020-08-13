<?php

namespace App\Repositories;

use App\LectureComment;
use App\ClassroomLiveComment;

class CommentRepository 
{
	/**
	 * Lecture comments data
	 * App\LectureComment
	 */
	private $lecture_comments;

	/**
	 * Classlive comments data
	 * App\ClassroomLiveComment
	 */
	private $classroom_live_comments;

	/**
	 * lecture comment data
	 * App\LectureComment
	 */
	private $lecture_comment;

	/**
	 * Classlive comment data
	 * App\ClassroomLiveComment
	 */
	private $classroom_live_comment;

	/**
	 * Retreive lecture's comments
	 *
	 * @author shelleran <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\LectureComment
	 */
	public function getLectureComments()
	{
		return $this->lecture_comments;
	}

	/**
	 * Retreive classlive's comments
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\ClassroomLiveComment
	 */
	public function getClassroomLiveComments()
	{
		return $this->classroom_live_comments;
	}

	/**
	 * Retreive lecture's comment
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\LectureComment
	 */
	public function getLectureComment()
	{
		return $this->lecture_comment;
	}

	/**
	 * Retreive classlive's comment
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return \App\ClassroomLiveComment
	 */
	public function getClassroomLiveComment()
	{
		return $this->classroom_live_comment;
	}

	/**
	 * Set lecture_comment property
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \App\LectureComment
	 * @return void
	 */
	public function setLectureComment($comment)
	{
		$this->lecture_comment = $comment;
	}

	/**
	 * Set classroom_live_comment property
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \App\ClassroomLiveComment
	 * @return void
	 */
	public function setClassroomLiveComment($comment)
	{
		$this->classroom_live_comment = $comment;
	}

	/**
	 * Get lecture's comments
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param int $id_lecture
	 * @param int $perPage
	 * @return void
	 */	
	public function getDataLectureComments($id_lecture, int $perPage): void
	{
		$comments = LectureComment::with('user')
					->where('lecture_id', $id_lecture)
					->paginate($perPage);
		$this->lecture_comments = $comments;
	}

	/**
	 * get lecture's comment
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param int $id_comment
	 * @return void
	 */
	public function getDataLectureComment($id_comment, $key = 'id'): void
	{
		$comment = LectureComment::where($key, $id_comment)->first();
		if(!$comment) {
			throw new \App\Exceptiosn\CommentNotFoundException();
		}
		$this->setLectureComment($comment);
	}

	/**
	 * Create new lecture comment data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function createNewLectureComment($request): void
	{
		try {
			$data = [
				'lecture_id'		=> $request->lecture_id,
				'user_id'			=> $request->user_id,
				'content'			=> $request->content
			];
			$comment = LectureComment::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
		$this->setLectureComment($comment);
	}

	/**
	 * Delete lecture comment data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function deleteDataLectureComment($lecture_comment_id = ''): void
	{
		try {
			if($lecture_comment_id != '') {
				$this->getDataLectureComment($lecture_comment_id);
			}
			$this->lecture_comment->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update lecture comment data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function updateDataLectureComment($request): void
	{
		try {
			$data = [
				'content'		=> $request->content
			];
			$this->comment->update($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data classroom live comments
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function getDataClassroomLiveComments($class_live, int $perPage): void
	{
		$comments = ClassroomLiveComment::with('user')
					->where('classroom_live_id', $class_live)
					->paginate($perPage);
		$this->classroom_live_comments = $comments;
	}

	/**
	 * Create new classroom live comment data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function createNewClassroomLiveComment($request): void
	{
		try {
			$data = [
				'classroom_live_id'	=> $request->classroom_live_id,
				'user_id'			=> $request->user_id,
				'content'			=> $request->content
			];
			$comment = ClassroomLiveComment::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
		$this->setClassroomLiveComment($comment);
	}
}