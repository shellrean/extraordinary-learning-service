<?php

use Illuminate\Database\Seeder;
use App\ClassroomSubject;

class ClassroomSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClassroomSubject::create([
        	'classroom_id'		=> 1,
        	'subject_id'		=> 1
        ]);
    }
}
