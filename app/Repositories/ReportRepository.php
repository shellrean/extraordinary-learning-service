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
    public function getDataRecapAbcents($classroom_id, $schedule_ids, $from = '', $end = '')
    {
        try {
            if($from == '') {
                $from = \Carbon\Carbon::today();
            }
            if($end == '') {
                $end = \Carbon\Carbon::today()->addDay(1);
            }

            $result_abcents = Abcent::whereIn('schedule_id', $schedule_ids)
            ->whereBetween('created_at', [$from, $end])
            ->get()
            ->map(function($item) {
                return [
                    'user_id'   => $item->user_id,
                    'schedule_id' => $item->schedule_id,
                    'isabcent' => $item->isabcent,
                    'reason' => $item->reason,
                    'created_at' => $item->created_at->format('d-m-Y'),
                    'time'  => $item->created_at->format('h:i:s A'),
                    'desc' => $item->desc,
                    'details' => $item->details
                ];
            })
            ->values();

            $days = \App\Schedule::whereIn('id', $schedule_ids)->get()->pluck('day');

            $begin = new \DateTime($from->format('Y-m-d'));
            $end = new \DateTime($end->format('Y-m-d'));

            $interval = new \DateInterval('P1D');
            $daterange = new \DatePeriod($begin, $interval ,$end);

            $students = ClassroomStudent::with([
                'student'   => function($query) {
                    $query->select('id','name','uid');
                }
            ])
            ->where('classroom_id', $classroom_id)
            ->get(); 

            $data = [];
            foreach($daterange as $date) {
                if(in_array($date->format('w'), $days->toArray())) {
                    array_push($data, $date->format('d-m-Y'));
                }
            }
            $dat = [];
            foreach($students as $s) {
                $total = 0;
                $absen = 0;
                $sick = 0;
                $alpha = 0;
                $permit = 0;
                $problem = 0;
                $new_dat['nis'] = $s->student->uid;
                $new_dat['name'] = $s->student->name;
                foreach($data as $key => $value) {
                    if(!$result_abcents->isEmpty()) {
                        $check = $result_abcents->where('created_at', $value)->first();
                        if($check) {
                            $new_dat[$key] = $check['isabcent'];
                            if($check['isabcent'] == '1') {
                                $total += 1;
                            }
                            if($check['isabcent'] == '0'){
                                $absen += 1;
                            }
                            switch ($check['reason']) {
                                case '1':
                                    $alpha += 1;
                                    break;
                                
                                case '2':
                                    $sick += 1;
                                break;
                                case '3':
                                    $permit += 1;
                                break;
                                case '4':
                                    $problem += 1;
                                break;
                                default:
                                    
                                break;
                            }
                        } else {
                            $new_dat[$key] = 'x';
                        }
                    } else {
                        $new_dat[$key] = 'x';
                    }
                }

                $new_dat['total'] = $total;
                $new_dat['absen'] = $absen; 
                $new_dat['alpha'] = $alpha;
                $new_dat['permit'] = $permit;
                $new_dat['sick'] = $sick;
                $new_dat['problem'] = $problem;
                array_push($dat, $new_dat);
            }

            $this->recap_abcents = [
                'dates' => $data,
                'data' => $dat
            ];
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