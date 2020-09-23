<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Schedule;
use App\Abcent;
use App\ClassroomStudent;

class ReportRepository
{
    /**
     * Data recap_abcent
     * @var Collection
     */
    private $recap_abcents;

    /**
     * Retreive data recap_abcent
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.0.1
     * @return self $recap_abcents
     */
    public function getRecapAbcents()
    {
        return $this->recap_abcents;
    }

    /**
     * Get data recapitulation abcent
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.0.1
     * @param $classroom_id
     * @param $from
     * @param $end
     * @return void
     */
    public function getDataRecapAbcents($classroom_id, $schedule_id, $from = '', $end = '')
    {
        try {
            if($from == '') {
                $from = \Carbon\Carbon::today();
            }
            if($end == '') {
                $end = \Carbon\Carbon::today()->addDay(1);
            }
            $abcents = Abcent::where('schedule_id', $schedule_id)
            ->whereBetween('created_at', [$from, $end])
            ->get();
            
            $classrooms = ClassroomStudent::with([
                'student'   => function($query) {
                    $query->select('id','name','uid');
                }
            ])
            ->where('classroom_id', $classroom_id)->get();

            $dat = [];
            foreach($classrooms as $student) {
                $data = $abcents->where('user_id', $student->student_id)->map(function($item) {
                    return [
                        'id'    => $item->id,
                        'user_id'=> $item->user_id,
                        'schedule_id' => $item->schedule_id,
                        'isabcent'  => $item->isabcent,
                        'reason'    => $item->reason,
                        'desc'  => $item->desc,
                        'details'   => $item->details,
                        'created_at' => $item->created_at->format('Y-m-d')
                    ];
                })
                ->values();

                $ret = [
                    'student'   => $student,
                    'abcents'   => $data
                ];
                array_push($dat, $ret);
            }

            $this->recap_abcents = $dat;
        } catch (\Exception $e) {
            throw new \App\Exceptions\ModelException($e->getMessage());
        }
    }
}