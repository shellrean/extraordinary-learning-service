<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class TeacherImport implements ToCollection, WithStartRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if($row->filter()->isNotEmpty()){
                $user = User::create([
                    'name'           	=> $row[0],
                    'email'        		=> $row[1],
                    'password'      	=> bcrypt($row[2]),
                    'role'				=> '1',
                    'isactive'          => true,
                    'uid'               => $row[3]
                ]);
            }
        }
    }

    public function startRow(): int
    {
    	return 2;
    }

    public function rules(): array
    {
    	return [
            '1'	=> 'unique:users,email',
            '3' => 'unique:users,uid'
    	];
    }
}
