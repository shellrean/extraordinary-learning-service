<?php

namespace App\Repositories;

use App\Standart;

class StandartRepository
{
	/**
	 * Data standart
	 * @var App\Standart
	 */
	private $standart;

	/**
	 * Data standarts
	 * @var Collection
	 */
	private $standarts;

	/**
	 * Retreive data standart
	 * 
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @return App\Standart
	 */
	public function getStandart()
	{
		return $this->standart;
	}

	/**
	 * Retreive data standarts
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.1
	 * @return Collection
	 */
	public function getStandarts()
	{
		return $this->standarts;
	}

	/**
	 * Set data standart property
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.1
	 * @param $standart
	 * @return void
	 */
	public function setStandart($standart)
	{
		$this->standart = $standart;
	}

	/**
	 * Get data standarts
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.1
	 * @param $perPage
	 * @param $techer_id
	 * @return void
	 */
	public function getDataStandarts(int $perPage, $teacher_id = null)
	{
		try {
			$standarts = Standart::with('children')->where('standart_id',0);
			if($teacher_id != null) {
				$standarts = $standarts->where('teacher_id', $teacher_id);
			}
			$this->standarts = $standarts->paginate($perPage);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Get data standart
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param $standart_id
	 * @param $exception default true
	 * @return void
	 */
	public function getDataStandart($standart_id, $exception = true)
	{
		try {
			$standart = Standart::where('id', $standart_id)->first();
			if(!$standart && $exception) {
				throw new \App\Exceptions\ModelNotFoundException($e->getMessage());
			}
			$this->setStandart($standart);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/** 
	 * Create data standart
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.1
	 * @param $request
	 * @return void
	 */
	public function createDataStandart($request)
	{
		try {
			$standart = Standart::create([
				'teacher_id'	=> $request->teacher_id,
				'standart_id'	=> $request->standart_id != '' ? $request->standart_id : 0,
				'type'			=> $request->type,
				'code'			=> $request->code,
				'body'			=> $request->body
			]);

			$this->setStandart($standart);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * Update data standart
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.1
	 * @param $request
	 * @param $standart_id
	 * @return void
	 */
	public function updateDataStandart($request, $standart_id)
	{
		try {
			$this->getDataStandart($standart_id);
			$this->standart->update([
				'teacher_id'	=> $request->teacher_id,
				'standart_id'	=> $request->standart_id,
				'type'			=> $request->type,
				'code'			=> $request->code,
				'body'			=> $request->body
			]);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}

	/**
	 * delete data standart
	 *
	 * @author shellrean <wandinak17@gamil.com>
	 * @since 1.0.1
	 * @param $standart_id
	 * @return void
	 */
	public function deleteDataStandart($standart_id)
	{
		try {
			Standart::where('standart_id', $standart_id)->delete();
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}