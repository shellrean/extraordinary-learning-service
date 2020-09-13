<?php

use Illuminate\Database\Seeder;

use App\ClassroomStudent;

class ClassroomStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClassroomStudent::create([
        	'student_id'	=> 5,
        	'classroom_id'	=> 1
        ]);

        ClassroomStudent::create([
            'student_id'    => 6,
            'classroom_id'  => 1
        ]);
    }
}
