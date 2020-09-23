<?php

namespace App\Http\Controllers\Api\v1;

use App\Repositories\ClassroomRepository;
use App\Repositories\ReportRepository;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Exports\RecapAbcentExport;
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
    public function recapAbcent(ReportRepository $reportRepository, ClassroomRepository $classroomRepository) 
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
        $reportRepository->getDataRecapAbcents($classroom_id, $schedule_id, $from, $end);

        $results = $reportRepository->getRecapAbcents();
        $begin = new \DateTime($from->format('Y-m-d'));
        $end = new \DateTime($end->format('Y-m-d'));

        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval ,$end);

        return Excel::download(new RecapAbcentExport($results, $daterange, $classroomRepository->getSchedule()->day), 'recapitulasi_abcent.xlsx');
    }
    
}
