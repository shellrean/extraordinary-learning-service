<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class StandartExportSpreet
{
    public static function export($standarts, $subject_name)
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
                'startColor' => array('argb' => 'FFCC99')
            ],
            'font'  => array(
                'size'  => 12,
            )
        ];
          
        $column = 'A';
        $row = 3;
        $sheet->setCellValue('A1', $subject_name);
        $spreadsheet->getActiveSheet()->mergeCells("A1:C1");
        $spreadsheet->getActiveSheet()->getRowDimension('1')->setRowHeight(52);
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');

        $sheet->setCellValue('A2', 'NO');
        $sheet->setCellValue('B2', 'KOMPETENSI INTI');
        $sheet->setCellValue('C2', 'KOMPETENSI DASAR');
        $sheet->getStyle('A2:C2')->applyFromArray($styleArray2);
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $spreadsheet->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $sheet->getStyle('A2')->getAlignment()->setVertical('center');
        $sheet->getStyle('B2')->getAlignment()->setVertical('center');
        $sheet->getStyle('C2')->getAlignment()->setVertical('center');

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(42);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(62);
        foreach($standarts as $key => $value) {
            $sheet->getStyle('A'.$row)->applyFromArray($styleArray);
            $sheet->getStyle('B'.$row)->applyFromArray($styleArray);
            $sheet->getStyle('C'.$row)->applyFromArray($styleArray);

            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A'.$row)->getAlignment()->setVertical('top');
            $sheet->getStyle('B'.$row)->getAlignment()->setVertical('top');
            $sheet->getStyle('C'.$row)->getAlignment()->setVertical('top');

            $sheet->getStyle('B'.$row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('C'.$row)->getAlignment()->setWrapText(true);
            
            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->setCellValue('B'.$row, $value->code.'. '. chr(13).str_replace("&nbsp;", "",strip_tags($value->body)));

            $childder = "";
            foreach($value->children as $child) {
                $childder .= $child->code.'. '. chr(13).str_replace("&nbsp;", "",strip_tags($child->body)).chr(10) . chr(13);
            }
            $spreadsheet->getActiveSheet()->getCell('C'.$row)->setValue($childder);
            $row++;
        }
        return $spreadsheet;
    }

}