<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TeacherImport implements ToCollection, WithStartRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if($row->filter()->isNotEmpty()){
                $check = User::where('email', $row[1])->first();
                $check2 = User::where('uid', $row[3])->first();
                if(!$check && !$check2) {
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
    }

    public function startRow(): int
    {
    	return 2;
    }
}
