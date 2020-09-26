<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RecapAbcentSpreet
{
    public static function export($datas, $dates, $day, $from, $end, $schedule)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
        ];

        $styleGood = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'baffb8')
            ],
            'font'  => array(
                'color' => array('rgb' => '149410')
            )
        ];

        $styleBad = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'fcb197')
            ],
            'font'  => array(
                'color' => array('rgb' => 'bd3506')
            )
        ];

        $sheet->setCellValue('A1', 'REKAPITULASI ABSENSI'.chr(10).$from->format('d-m-Y').' SAMPAI '.$end->format('d-m-Y'));
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
        $sheet->mergeCells("A1:C1");
        $sheet->mergeCells("A2:C2");
        $sheet->setCellValue('A2', '('.$schedule->from_time.' - '.$schedule->end_time.')');
        $sheet->getRowDimension('1')->setRowHeight(52);
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');
        $sheet->getStyle('A3:C3')->applyFromArray($styleArray);
        
        $sheet->getRowDimension('3')->setRowHeight(115);
        $sheet->getColumnDimension('C')->setWidth(45);
        
        $result = [];
        foreach($datas as $item) {
            $data = [
                'nis'   => $item['student']['student']['uid'],
                'name'  => $item['student']['student']['name']
            ];
            $total = 0;
            $absen = 0;
            $sick = 0;
            $alpha = 0;
            $permit = 0;
            $problem = 0;
            foreach($dates as $date) {
                if($date->format('w') == $day){
                    $collect = collect($item['abcents'])->where('created_at', $date->format('Y-m-d'))->first();
                    $data[$date->format('Y-m-d')] = $collect == NULL ? '-' : $collect['isabcent'];
                    if($collect != NULL && $collect['isabcent'] == '1') {
                        $total += 1;
                    }
                    if($collect != NULL && $collect['isabcent'] == '0'){
                        $absen += 1;
                    }
                    if($collect != NULL) {
                        switch ($collect['reason']) {
                            case '1':
                                $alpha += 1;
                                break;
                            
                            case '2':
                                $sick += 1;
                            
                            case '3':
                                $permit += 1;
                            
                            case '4':
                                $problem += 1;

                            default:
                                
                                break;
                        }
                    }
                }
            }
            $data['total'] = $total;
            $data['absen'] = $absen; 
            $data['alpha'] = $alpha;
            $data['permit'] = $permit;
            $data['sick'] = $sick;
            $data['problem'] = $problem;
            array_push($result, $data);
        }
        
        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NIS');
        $sheet->setCellValue('C3', 'NAMA');
        $data = [];
        foreach($dates as $date) {
            if($date->format('w') == $day) {
                array_push($data, $date->format('d-m-Y'));
            }
        }
        
        $column_header = 'D';
        foreach($data as $key => $value) {
            $sheet->getStyle($column_header.'3')->applyFromArray($styleArray);
            
            $sheet->setCellValue($column_header.'3', $value);
            $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
            
            $sheet->getColumnDimension($column_header)->setWidth(3);
            $column_header++;
        }
        $sheet->setCellValue($column_header.'3', 'MASUK');
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        $column_header++;
        $sheet->setCellValue($column_header.'3', 'TIDAK MASUK');
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray);
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        $column_header++;
        $sheet->setCellValue($column_header.'3', 'TANPA KETERANGAN');
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray);
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        $column_header++;
        $sheet->setCellValue($column_header.'3', 'IZIN');
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray);
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        $column_header++;
        $sheet->setCellValue($column_header.'3', 'SAKIT');
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray);
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        $column_header++;
        $sheet->setCellValue($column_header.'3', 'MASALAH');
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray);
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        
        
        $row = 4;
        $column_header++;
        foreach($result as $key => $value) {
            $val = array_values($value);
            $sheet->getStyle('A'.$row)->applyFromArray($styleArray);
            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->getStyle('A'.$row)->getAlignment()->setWrapText(true);

            $column = 'B';
            $key2 = 0;
            while ($column != $column_header) {
                if($val[$key2] == '0') {
                    $sheet->getStyle($column.$row)->applyFromArray($styleBad);    
                }
                elseif($val[$key2] == '1') {
                    $sheet->getStyle($column.$row)->applyFromArray($styleGood);
                } else {
                    $sheet->getStyle($column.$row)->applyFromArray($styleArray);
                }
                $sheet->setCellValue($column.$row, $val[$key2]);

                $column++;
                $key2++;
            }
            $row++;
        }

        return $spreadsheet;
    }
}