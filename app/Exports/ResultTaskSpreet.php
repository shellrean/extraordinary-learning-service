<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ResultTaskSpreet extends ExportExcel
{
    public static function export($datas)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NILAI TUGAS');
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1')->getAlignment()->setVertical('center');
        $sheet->mergeCells("A1:C1");

        $sheet->getRowDimension('1')->setRowHeight(52);
        $sheet->getRowDimension('3')->setRowHeight(45);
        $sheet->getColumnDimension('C')->setWidth(45);
        $sheet->getColumnDimension('D')->setWidth(26);
        $sheet->getColumnDimension('E')->setWidth(26);

        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'NIS');
        $sheet->setCellValue('C3', 'NAMA');
        $sheet->setCellValue('D3', 'TANGGAL PENGEUMPULAN');
        $sheet->setCellValue('E3', 'TANGGAL PENILAIAN');
        $sheet->setCellValue('F3', 'NILAI');

        $sheet->getStyle('A3:F3')->applyFromArray(self::styleGeneral());

        $row = 4;
        foreach($datas as $key => $data) {
            $column = 'A';
            $sheet->setCellValue($column.$row, $key+1);
            $sheet->getStyle($column.$row)->applyFromArray(self::styleGeneral());
            $column++;

            $sheet->setCellValue($column.$row, $data->student->uid);
            $sheet->getStyle($column.$row)->applyFromArray(self::styleGeneral());
            $column++;

            $sheet->setCellValue($column.$row, $data->student->name);
            $sheet->getStyle($column.$row)->applyFromArray(self::styleGeneral());
            $column++;

            $sheet->setCellValue($column.$row, $data->created_at->format('d-m-Y H:m'));
            $sheet->getStyle($column.$row)->applyFromArray(self::styleGeneral());
            $column++;

            $sheet->setCellValue($column.$row, $data->result->created_at->format('d-m-Y H:m'));
            $sheet->getStyle($column.$row)->applyFromArray(self::styleGeneral());
            $column++;

            $sheet->setCellValue($column.$row, $data->result->point);
            $sheet->getStyle($column.$row)->applyFromArray(self::styleGeneral());
        }

        return $spreadsheet;
    }
}