<?php

namespace App\Imports;

use App\User;
use App\ClassroomStudent;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentImport implements ToCollection, WithStartRow
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
                $check = User::where('email', $row[1])->first();
                $check2 = User::where('uid', $row[3])->first();
                if(!$check && !$check2) {
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
    }

    public function startRow(): int
    {
    	return 2;
    }
}
