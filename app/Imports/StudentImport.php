<?php

namespace App\Imports;

use App\User;
use App\ClassroomStudent;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;


class StudentImport implements ToCollection, WithStartRow, WithValidation
{
    private $classroom_id;

    public function __construct($classroom_id)
    {
        $this->classroom_id = $classroom_id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if($row->filter()->isNotEmpty()){
                $user = User::create([
                    'name'              => $row[0],
                    'email'              => $row[1],
                    'password'          => bcrypt($row[2]),
                    'role'               => '2',
                    'isactive'          => true,
                    'uid'               => $row[3]
                ]);
                ClassroomStudent::create([
                    'student_id'        => $user->id,
                    'classroom_id'      => $this->classroom_id
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
