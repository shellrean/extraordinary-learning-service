<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithProperties;

class RecapAbcentExport implements FromCollection, WithHeadings, ShouldAutoSize, WithProperties
{
    private $collection;
    private $dates;
    private $day;

    public function properties(): array
    {
        return [
            'creator'        => 'Extraordinary LMS ',
            'lastModifiedBy' => 'Extraordinary LMS',
            'title'          => 'Rekapitulasi Absensi',
            'description'    => 'Rekapitulasi Absensi',
            'subject'        => 'Rekapitulasi',
            'keywords'       => 'Rekapitulasi,lms,extraordinary',
            'category'       => 'Rekapitulasi',
            'manager'        => 'Extraordinary LMS',
            'company'        => 'Extraordinary',
        ];
    }
    
	public function __construct($collection, $dates, $day)
	{
        $this->collection = $collection;
        $this->dates = $dates;
        $this->day = $day;
    }
    
    public function collection()
    {
        $result = [];
        foreach($this->collection as $item) {
            $data = [
                'nis'   => $item['student']['student']['uid'],
                'name'  => $item['student']['student']['name']
            ];
            $total = 0;
            $absen = 0;
            foreach($this->dates as $date) {
                if($date->format('w') == $this->day){
                    $collect = collect($item['abcents'])->where('created_at', $date->format('Y-m-d'))->first();
                    $data[$date->format('Y-m-d')] = $collect == NULL ? '-' : $collect['isabcent'];
                    if($collect != NULL && $collect['isabcent'] == '1') {
                        $total += 1;
                    }
                    if($collect != NULL && $collect['isabcent'] == '0'){
                        $absen += 1;
                    }
                }
            }
            $data['total'] = $total;
            $data['absen'] = $absen; 
            array_push($result, $data);
        }
        return collect($result);
    }

    public function headings(): array
    {
        $data = [
            'NIS',
            'NAMA'
        ];
        foreach($this->dates as $date) {
            if($date->format('w') == $this->day) {
                array_push($data, $date->format('d-m-Y'));
            }
        }
        array_push($data, 'MASUK');
        array_push($data, 'TIDAK MASUK');
        return $data;
    }
}
