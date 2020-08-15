<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbcentSubjectClassroomExport implements FromCollection, WithHeadings, ShouldAutoSize
{
	private $collection;

	public function __construct($collection)
	{
		$this->collection = $collection;
	}
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->collection->map(function ($item, $index){
        	return [
        		'no'	=> $index+1,
        		'name' => $item->user->name,
        		'hadir' => ($item->isabcent == 1 ? 'Hadir': 'Tidak hadir' ),
        		'type' => isset($item->details['type']) ? $item->details['type'] : '-' ,
        		'desc' => $item->desc
        	];
        });
    }

    public function headings(): array
    {
        return [
            '#',
            'Nama siswa',
            'Hadir/Tidak',
            'Keterangan',
            'Penjelasan'
        ];
    }
}
