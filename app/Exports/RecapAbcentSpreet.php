<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RecapAbcentSpreet
{
    public static function export($result, $from, $end)
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
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');
        $sheet->mergeCells("A1:C1");

        $sheet->setCellValue('A2', '');
        $sheet->mergeCells("A2:C2");

        $sheet->getStyle('A3:C3')->applyFromArray($styleArray);
        $sheet->getRowDimension('1')->setRowHeight(52);
        $sheet->getRowDimension('3')->setRowHeight(115);
        $sheet->getColumnDimension('C')->setWidth(45);

        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NIS');
        $sheet->setCellValue('C3', 'NAMA');
        $column_header = 'D';
        foreach($result['dates'] as $key => $value) {
            $sheet->setCellValue($column_header.'3', $value);
            $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
            $sheet->getStyle($column_header.'3')->applyFromArray($styleArray2);
            
            $sheet->getColumnDimension($column_header)->setWidth(3);
            $column_header++;
        }
        $sheet->setCellValue($column_header.'3', 'MASUK');
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray3);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        $column_header++;
        $sheet->setCellValue($column_header.'3', 'TIDAK MASUK');
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray3);
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        $column_header++;
        $sheet->setCellValue($column_header.'3', 'TANPA KETERANGAN');
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray3);
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        $column_header++;
        $sheet->setCellValue($column_header.'3', 'IZIN');
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray3);
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        $column_header++;
        $sheet->setCellValue($column_header.'3', 'SAKIT');
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray3);
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getColumnDimension($column_header)->setWidth(3);
        $column_header++;
        $sheet->setCellValue($column_header.'3', 'MASALAH');
        $sheet->getStyle($column_header.'3')->applyFromArray($styleArray3);
        $sheet->getStyle($column_header.'3')->getAlignment()->setTextRotation(90);
        $sheet->getColumnDimension($column_header)->setWidth(3);

        $row = 4;
        $column_header++;
        foreach($result['data'] as $key => $value) {
            $val = array_values($value);
            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->getStyle('A'.$row)->applyFromArray($styleArray);
            $sheet->getStyle('A'.$row)->getAlignment()->setWrapText(true);

            $column = 'B';
            foreach($val as $key2 => $value2) {
                $sheet->getStyle($column.$row)->applyFromArray($styleArray);
                if(is_array($value2) ){
                    $sheet->setCellValue($column.$row, $value2);
                } else {
                    $sheet->setCellValue($column.$row, $value2);
                }
                $column++;
            }   
            $row++;
        }

        return $spreadsheet;
    }
}