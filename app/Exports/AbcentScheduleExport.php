<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbcentScheduleExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        $reasons = [
            "0" => "-",
            "1" => "Tanpa Keterangan",
            "2" => "Sakit",
            "3" => "Izin",
            "4" => "Masalah"
        ];
        return $this->collection->map(function ($item, $index) use ($reasons){
        	return [
        		'no'	=> $index+1,
        		'name' => $item->user->name,
        		'hadir' => ($item->isabcent == 1 ? 'Hadir': 'Tidak hadir' ),
                'reason' => $reasons[$item->reason],
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
            'Tambahan'
        ];
    }
}
