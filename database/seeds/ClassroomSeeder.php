<?php

use Illuminate\Database\Seeder;
use App\Classroom;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Classroom::create([
        	'teacher_id'		=> 2,
        	'name'				=> 'XII IPA',
        	'grade'				=> 12,
        	'group'				=> 'IPA',
        	'settings'			=> []
        ]);
    }
}
