<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class StudentImport implements ToModel, WithStartRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new User([
            'name'           	=> $row[0],
            'email'        		=> $row[1],
            'password'      	=> bcrypt($row[2]),
            'role'				=> '2',
            'isactive'          => true
        ]);
    }

    public function startRow(): int
    {
    	return 2;
    }

    public function rules(): array
    {
    	return [
    		'1'	=> 'unique:users,email'
    	];
    }
}
