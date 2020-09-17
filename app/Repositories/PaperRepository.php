<?php

namespace App\Repositories;

use App\Paper;

class PaperRepository
{
    /**
     * Data paper
     * 
     * @var \App\Paper
     */
    private $paper;

    /**
     * Data papers
     * 
     * @var Collection
     */
    private $papers;

    /**
     * Retreive data paper
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.0.1
     * @return \App\Paper
     */
    public function getPaper()
    {
        return $this->paper;
    }

    /**
     * Retreive data papers
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.0.1
     * @return Collection
     */
    public function getPapers()
    {
        return $this->papers;
    }

    /**
     * Set data paper
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.0.1
     * @param $paper
     * @return void
     */
    public function setPaper($paper)
    {
        $this->paper = $paper;
    }

    /**
     * Get data paper
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.0.1
     * @param $paper_id
     * @return void
     */
    public function getDataPaper($paper_id, bool $exception = true)
    {
        try {
            $paper = Paper::where('id', $paper_id)->first();
            if(!$paper && $exception) {
                throw new \App\Exceptions\ModelNotFoundException('paper not found');
            }
            $this->setPaper($paper);
        } catch (\Exception $e) {
            throw new \App\Exceptions\ModelException($e->getMessage());
        }
    }

    /**
     * Get data papers
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.0.1
     * @param $teacher_id
     * @return void
     */
    public function getDataPapers($type, $teacher_id = '', $classroom_subject_id = '')
    {
        try {
            $papers = Paper::with([
                'classroom_subject',
                'teacher'
            ]);
            if($teacher_id != '') {
                $papers = $papers->where('teacher_id', $teacher_id);
            }
            if($classroom_subject_id != '') {
                $papers = $papers->where('classroom_subject_id', $classroom_subject_id);
            }
            $papers = $papers->where('type', $type)->get();

            $this->papers = $papers;
        } catch (\Exception $e) {
            throw new \App\Exceptions\ModelException($e->getMessage());
        }
    }

    /**
     * Crete data paper
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.0.1
     * @param $request
     * @return void
     */
    public function createDataPaper($request)
    {
        try {
            $data = [
                'classroom_subject_id'  => $request->classroom_subject_id,
                'teacher_id'            => $request->teacher_id,
                'type'                  => $request->type,
                'name'                  => $request->name,
                'body'                  => $request->body,
                'file_location'         => $request->file_location,
                'settings'              => $request->settings
            ];
            $paper = Paper::create($data);
            $this->setPaper($paper);
        } catch (\Exception $e) {
            throw new \App\Exceptions\ModelException($e->getMessage());
        }
    }

    /**
     * Update data paper
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.0.1
     * @param $request
     * @param $paper_id
     * @return void
     */
    public function updateDataPaper($request, $paper_id = '')
    {
        try {
            if($paper_id != '') {
                $this->getDataPaper($paper_id);
            }
            if (!is_subclass_of($this->paper, 'Illuminate\Database\Eloquent\Model')) {
                throw new \App\Exceptions\ModelException('not instance of eloquent model');
            }
            $data_update = [
                'classroom_subject_id'  => $request->classroom_subject_id,
                'type'                  => $request->type,
                'name'                  => $request->name,
                'body'                  => $request->body,
                'settings'              => $request->settings
            ];
            if($request->file_location) {
                $data_update['file_location'] = $request->file_location;
            }
            $this->paper->update($data_update);
        } catch (\Exception $e) {
            throw new \App\Exceptions\ModelException($e->getMessage());
        }
    }

    /**
     * Delete data paper
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.0.1
     * @param $paper_id
     * @return void
     */
    public function deleteDataPaper($paper_id = ''): void
    {
        try {
            if($paper_id != '') {
                Paper::where('id', $paper_id)->delete();
                return;
            }
            if (!is_subclass_of($this->paper, 'Illuminate\Database\Eloquent\Model')) {
                throw new \App\Exceptions\ModelException('not instance of eloquent model');
            }
            $this->paper->delete();
        } catch (\Exception $e) {
            throw new \App\Exceptions\ModelException($e->getMessage());
        }
    }
}