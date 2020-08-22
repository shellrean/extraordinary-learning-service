<?php

namespace App\Repositories;

use App\Info;

class InfoRepository
{
	/**
	 * Data infos
	 * @var Collection
	 */
	private $infos;

	/**
	 * Data info
	 * @var App\Info
	 */
	private $info;

	/**
	 * Retreive data infos
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @return Collection
	 */
	public function getInfos()
	{
		return $this->infos;
	}

	/**
	 * Retreive data info
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @return App\Info
	 */
	public function getInfo()
	{
		return $this->info;
	}

	/** 
	 * Set data info
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @return void
	 */
	public function setInfo($info)
	{
		$this->info = $info;
	}

	/**
	 * Get data infos
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $per_page
	 * @return void
	 */
	public function getDataInfos(int $per_page, $status = '')
	{
		try {
			$infos = Info::orderBy('id', 'desc');
			if($status != '') {
				$infos = $infos->where('status', $status);
			}
			$this->infos = $infos->paginate($per_page);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data info
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $info_id
	 * @return void
	 */
	public function getDataInfo($info_id, bool $exception = true)
	{
		try {
			$info = Info::find($info_id);
			if(!$info && $exception) {
				throw new \App\Exceptions\ModelNotFoundException('info not found');
			}
			$this->setInfo($info);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Create new info
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param \App\Http\Reqeusts
	 * @return void
	 */
	public function createNewInfo($request)
	{
		try {
			$data = [
				'user_id'	=> $request->user_id,
				'title'		=> $request->title,
				'body'		=> $request->body,
				'status'	=> $request->status,
				'settings'	=> $request->settings
			];

			$info = Info::create($data);
			$this->setInfo($info);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data info
	 *
	 * @author shellran <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param \App\Http\Reqeusts
	 * @return void
	 */
	public function updateDataInfo($request, $info_id = '')
	{
		try {
			if(!$info_id == '') {
				$this->getDataInfo($info_id);
			}
			$data = [
				'title'		=> $request->title,
				'body'		=> $request->body,
				'status'	=> $request->status,
				'settings'	=> $request->settings
			];

			$this->info->update($data);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Delete data info
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.0
	 * @param $info_id
	 * @return void
	 */
	public function deleteDataInfo($info_id)
	{
		try {
			info::where('id', $info_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}