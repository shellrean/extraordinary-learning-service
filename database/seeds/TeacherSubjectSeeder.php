<?php

use Illuminate\Database\Seeder;
use App\TeacherSubject;

class TeacherSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TeacherSubject::create([
        	'teacher_id'		=> 2,
        	'subject_id'		=> 1
        ]);
    }
}
