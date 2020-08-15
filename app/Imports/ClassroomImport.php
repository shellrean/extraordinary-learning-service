<?php

namespace App\Imports;

use App\Classroom;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ClassroomImport implements ToModel, WithStartRow, WithValidation
{
   /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new Classroom([
            'name'           	=> $row[0],
            'invitation_code' 	=> strtoupper(date('d').uniqid()),
            'grade'				=> $row[1],
            'group'				=> $row[2],
            'teacher_id'		=> $row[3]
        ]);
    }

    public function startRow(): int
    {
    	return 2;
    }

    public function rules(): array
    {
    	return [
    		'3'	=> 'exists:users,id'
    	];
    }
}
