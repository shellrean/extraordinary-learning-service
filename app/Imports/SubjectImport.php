<?php

namespace App\Imports;

use App\Subject;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SubjectImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Subject([
            'name'      => $row[0],
            'description' => $row[1],
            'settings'    => []
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
