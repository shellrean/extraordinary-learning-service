<?php

namespace App\Imports;

use App\Subject;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SubjectImport implements ToCollection, WithStartRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            if($row->filter()->isNotEmpty()){
                Subject::create([
                    'name'      => $row[0],
                    'description' => $row[1],
                    'settings'    => []
                ]);
            }
        }
    }
    public function startRow(): int
    {
        return 2;
    }
}
