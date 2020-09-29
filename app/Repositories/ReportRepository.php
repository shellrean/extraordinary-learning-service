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
     * Data recap_result_exams
     * @var Collection
     */
    private $recap_result_exams;

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
     * Retreive data recap_result_exams
     * 
     * @author shellrean <wandinak17@gmail.com>
     * @since 1.1.0
     * @return self $recap_abcents
     */
    public function getRecapResultExams()
    {
        return $this->recap_result_exams;
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

    /**
     * Get data recapitulation result exam
     * 
     * @author shellrean <wandinak17@ggmail.com>
     * @since 1.1.0
     * @param array $exam_ids
     * @param $classroom_id
     * @return void
     */
    public function getDataRecapResultExams(array $exam_ids, $classroom_id)
    {
        try {
            $classroom_students = ClassroomStudent::with([
                'student'   => function($query) {
                    $query->select('id','name','uid');
                }
            ])
            ->where(function($query) use ($classroom_id) {
                $query->where('classroom_id', $classroom_id)
                ->whereHas('student');
            })
            ->get();

            $student_ids = $classroom_students->pluck('student_id')->toArray();

            $result = [];
            foreach($exam_ids as $exam_id) {
                $res = DB::table('exam_results')->where('exam_schedule_id', $exam_id)->get();
                if($res != null) {
                    $data['schedule_id'] = $exam_id;
                    $data['data'] = $res;
                    array_push($result, $data);
                }
            }
            $rest = [];
            foreach($classroom_students as $student) {
                $new_data['nis'] = $student->student->uid;
                $new_data['nama'] = $student->student->name;
                foreach($result as $key => $resu) {
                    $new = collect($resu['data'])->where('student_id', $student->student->id)->first();
                    if($new != null) {
                        $new_data['Ulangan ke '.$key] = $new->result;
                    } else {
                        $new_data['Ulangan ke '.$key] = '-';
                    }
                }
                array_push($rest, $new_data);
            }

            $this->recap_result_exams = collect($rest)->sortBy('name')->values();
        } catch (\Exception $e) {
            throw new \App\Exceptions\ModelException($e->getMessage());
        }
    }
}