<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\DB;

class RecapResultTask extends ExportExcel
{

    public static function export($result)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'REKAPITULASI NILAI TUGAS');
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');
        $sheet->mergeCells("A1:C1");

        $sheet->getRowDimension('1')->setRowHeight(52);
        $sheet->getRowDimension('3')->setRowHeight(115);
        $sheet->getColumnDimension('C')->setWidth(45);
        
        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NIS');
        $sheet->setCellValue('C3', 'NAMA');
        
        $sheet->getStyle('A3:C3')->applyFromArray(self::styleGeneral());

        $column_header = 'D';
        foreach($result['tasks'] as $task) {
            $sheet->setCellValue($column_header.'3', $task['title'].chr(13).$task['created_at']);

            $sheet->getStyle($column_header.'3')->applyFromArray(self::styleYellow());
            $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
            $sheet->getStyle($column_header.'3')->getAlignment()->setWrapText(true);

            $column_header++;
        }
        $sheet->setCellValue($column_header.'3', 'RATA - RATA');
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getStyle($column_header.'3')->applyFromArray(self::styleYellowDark());

        $row = 4;
        $column_header++;
        foreach($result['data'] as $key => $data) {
            $data = array_values($data);

            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->getStyle('A'.$row)->applyFromArray(self::styleGeneral());
            
            $count = 0;
            $sum = 0;

            $column = 'B';
            foreach($data as $key2 => $value) {
                $sheet->setCellValue($column.$row, $value);
                $sheet->getStyle($column.$row)->applyFromArray(self::styleGeneral());
                if($key2 > 1) {
                    if(is_numeric($value)) {
                        if($value < 60) {
                            $sheet->getStyle($column.$row)->applyFromArray(self::styleBad());
                        }
                        $sum += $value;
                    }
                    $sheet->getStyle($column.$row)->getAlignment()->setHorizontal('center');
                    $count++;
                }
                $column++;
            }

            if($sum != 0) {
                $sheet->setCellValue($column.$row, number_format($sum/$count,2));
                $sheet->getStyle($column.$row)->applyFromArray(self::styleGeneral());
                $sheet->getStyle($column.$row)->getAlignment()->setHorizontal('center');
            }
            else {
                $sheet->setCellValue($column.$row, 0);
                $sheet->getStyle($column.$row)->applyFromArray(self::styleGeneral());
                $sheet->getStyle($column.$row)->getAlignment()->setHorizontal('center');
            }

            $row++;
        }

        return $spreadsheet;
    }
}