<?php

namespace App\Repositories;

use App\Comment;

class CommentRepository 
{
	/**
	 * Comments data
	 * App\Comment
	 */
	private $comments;

	/**
	 * Comment data
	 * App\Commment
	 */
	private $comment;

	/**
	 * Retreive lecture's comments
	 *
	 * @author shelleran <wandinak17@gmail.com>
	 * @return \App\Comment
	 */
	public function getComments()
	{
		return $this->comments;
	}

	/**
	 * Retreive lecture's comment
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @return \App\Comment
	 */
	public function getComment()
	{
		return $this->comment;
	}

	/**
	 * Set comment property
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @param \App\Comment
	 * @return void
	 */
	public function setComment($comment)
	{
		$this->comment = $comment;
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
	public function getDataComments($id_lecture, int $perPage, string $type = 'lecture'): void
	{
		$comments = Comment::with('user')
					->where('lecture_id', $id_lecture)
					->where('type', $type)
					->paginate($perPage);
		$this->comments = $comments;
	}

	/**
	 * get lecture's comment
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param int $id_comment
	 * @return void
	 */
	public function getDataComment($id_comment, $key = 'id'): void
	{
		$comment = Comment::where($key, $id_comment)->first();
		if(!$comment) {
			throw new \App\Exceptiosn\CommentNotFoundException();
		}
		$this->setComment($comment);
	}

	/**
	 * Create new comment data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 * @return void
	 */
	public function createNewComment($request): void
	{
		try {
			$data = [
				'lecture_id'		=> $request->lecture_id,
				'user_id'			=> $request->user_id,
				'content'			=> $request->content
			];
			$comment = Comment::create($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
		$this->setComment($comment);
	}

	/**
	 * Delete comment data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function deleteDataComment(): void
	{
		try {
			$this->comment->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update comment data
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function updateDataComment($request): void
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
}