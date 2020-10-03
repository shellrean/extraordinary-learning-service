<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\ClassroomRepository;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Repositories\ReportRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Exports\RecapAbcentSpreet;
use App\Exports\RecapAbcentExport;
use App\Exports\RecapResultExam;
use App\Exports\RecapResultTask;
use App\Actions\SendResponse;

class ReportController extends Controller
{
    /**
    * Get recapitulation abcent
    * 
    * @author shellrean <wandinak17@gmail.com>
    * @param $classroom_id
    * @return \App\Actions\SendResponse
    */
    public function recapAbcentExcel(ReportRepository $reportRepository, ClassroomRepository $classroomRepository) 
    {
        $classroom_id = isset(request()->c) && request()->c != ''
                ? request()->c
                : '';
        $schedule_id = isset(request()->s) && request()->s != ''
                ? request()->s
                : '';
        if($schedule_id == '' || $classroom_id == '') {
            return SendResponse::badRequest('request parameter invalid');
        }
        $schedule_ids = explode(',', $schedule_id);
        $from = isset(request()->f) && request()->f != ''
                ? request()->f
                : '';
        if($from != '') {
            try {
                $from = \Carbon\Carbon::parse($from);
            } catch (\Exception $e) {
                $from = \Carbon\Carbon::today()->addDay(1);
            }
        } else {
            $from = \Carbon\Carbon::today()->addDay(1);
        }
        $end = isset(request()->e) && request()->e != ''
                ? request()->e
                : '';
        if($end != '') {
            try {
                $end = \Carbon\Carbon::parse($end)->addDay(1);
            } catch (\Exception $e) {
                $end = \Carbon\Carbon::today()->addDay(1);
            }
        } else {
            $from = \Carbon\Carbon::today()->addDay(1);
        }
        $classroomRepository->getDataSchedule($schedule_id);
        $reportRepository->getDataRecapAbcents($classroom_id, $schedule_ids, $from, $end);

        $results = $reportRepository->getRecapAbcents();

        $spreadsheet = RecapAbcentSpreet::export($results, $from, $end);
        $writer = new Xlsx($spreadsheet);
        

        $filename = 'Rekapitulasi absensi dari '.$from->format('d-m-Y').'_'.$end->format('d-m-Y');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
        $writer->save('php://output');
    }

    /**
     * Recapitulation result exams
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @param Query
     * @return \App\Actions\SendResponse
     */
    public function recapResultExams(ReportRepository $reportRepository)
    {
        $exams = isset(request()->e) && request()->e != ''
                ? request()->e
                : '';
        $classroom = isset(request()->c) && request()->c != ''
                ? request()->c
                : '';

        if($exams == '' || $classroom == '') {
            return SendResponse::badRequest('invalid parameter');
        }

        $exam_ids = explode(',', $exams);
        $classroom_id = $classroom;

        $reportRepository->getDataRecapResultExams($exam_ids, $classroom_id);
        $data = $reportRepository->getRecapResultExams();

        $spreadsheet = RecapResultExam::export($data, $exam_ids);
        $writer = new Xlsx($spreadsheet);

        $filename = 'Rekapitulasi nilai ulangan'.$exams.$classroom;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
        $writer->save('php://output');
    }

    /**
     * Recapitulation result tasts
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @param Query
     * @return \App\Actions\SendResponse
     */
    public function recapResultTasks(ReportRepository $reportRepository)
    {
        $tasks = isset(request()->t) && request()->t != ''
                ? request()->t
                : '';
        $classroom = isset(request()->c) && request()-> c != ''
                ? request()->c
                : '';
        if($tasks == '' || $classroom == '') {
            return SendResponse::badRequest('invalid parameter');
        }

        $task_ids = explode(',', $tasks);
        $classroom_id = $classroom;

        $reportRepository->getDataRecapResultTasks($task_ids, $classroom_id);

        $spreadsheet = RecapResultTask::export($reportRepository->getRecapResultTaks());
        $writer = new Xlsx($spreadsheet);

        $filename = 'Rekapitulasi nilai tugas'.$tasks.$classroom;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
        $writer->save('php://output');
    }
}
