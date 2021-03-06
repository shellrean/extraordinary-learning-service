<?php

namespace App\Imports;

use App\User;
use App\Classroom;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ClassroomImport implements ToCollection, WithStartRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if($row->filter()->isNotEmpty()){
                $user = User::where('uid', $row[3])->first();
                if($user && $user->role == '1') {
                    Classroom::create([
                        'name'           	=> $row[0],
                        'grade'				=> $row[1],
                        'group'				=> $row[2],
                        'teacher_id'		=> $user->id,
                        'settings'          => []
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
