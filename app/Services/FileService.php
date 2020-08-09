<?php

namespace App\Services;

class FileService 
{
	/**
	 * File detail
	 */
	public $fileDetail;

	/**
	 * Store data to storage
	 *
	 * @author shellrean <wandinak17@gmail.com>
	 * @since 1.0.0
	 * @param \Illuminate\Http\Request
	 */
	public function store($request, string $name = 'file'): void
	{
		try {
			$file = $request->file($name);
			$filename = date('Ymd').'-'.uniqid().'.'.$file->getClientOriginalExtention();
			$path = $file->storeAs('public/', $filename);

			$this->fileDetail = collect([
				'filename'		=> $filename,
				'path'			=> $path
			]);
		} catch (\Exception $e) {
			throw new \App\Exceptions\ModelException($e->getMessage());
		}
	}
}