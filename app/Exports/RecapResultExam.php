<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

class RecapResultExam
{
    public static function export($datas, $schedule_ids)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $result = [];
        foreach($schedule_ids as $exam_id) {
            $res = DB::table('exam_schedules')->where('id', $exam_id)->first();
            if($res != null) {
                array_push($result, $res);
            };
        }

        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
        ];

        $styleArray2 = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FFFF99')
            ],
        ];

        $styleArray3 = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FFCC99')
            ],
        ];
        
        

        $sheet->setCellValue('A1', 'REKAPITULASI HASIL ULANGAN');
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
        $sheet->mergeCells('A1:C1');
        $sheet->getRowDimension('1')->setRowHeight(52);
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');
        
        $sheet->getRowDimension('3')->setRowHeight(170);
        $sheet->getColumnDimension('C')->setWidth(45);


        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NIS');
        $sheet->setCellValue('C3', 'NAMA');
        $sheet->getStyle('A3:C3')->applyFromArray($styleArray);
        
        $column_header = 'D';
        foreach($result as $res) {
            $sheet->setCellValue($column_header.'3', $res->name.chr(13).$res->date.'('.$res->start_time.')');
            
            $sheet->getStyle($column_header.'3')->applyFromArray($styleArray2);
            $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
            $sheet->getStyle($column_header.'3')->getAlignment()->setWrapText(true);

            $column_header++;
        }
        $sheet->setCellValue($column_header.'3', 'RATA - RATA');
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray3);
        
        $row = 4;
        $column_header++;
        foreach($datas as $key => $data) {
            $data = array_values($data);
            $column = 'B';
            $count = 0;
            $sum = 0;
            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->getStyle('A'.$row)->applyFromArray($styleArray);

            foreach($data as $key2 => $value) {
                $sheet->setCellValue($column.$row, $value);
                $sheet->getStyle($column.$row)->applyFromArray($styleArray);
                if($key2 > 1) {
                    if(is_numeric($value) == '1') {
                        $sum += $value;
                    }
                    $count++;
                    $sheet->getStyle($column.$row)->getAlignment()->setHorizontal('center');
                }
                $column++;
            }

            if($sum != 0) {
                $sheet->setCellValue($column.$row, number_format($sum/$count,2));
                $sheet->getStyle($column.$row)->applyFromArray($styleArray);
                $sheet->getStyle($column.$row)->getAlignment()->setHorizontal('center');
            }
            else {
                $sheet->setCellValue($column.$row, 0);
                $sheet->getStyle($column.$row)->applyFromArray($styleArray);
                $sheet->getStyle($column.$row)->getAlignment()->setHorizontal('center');
            }
            $row++;
        }

        return $spreadsheet;
    }
}