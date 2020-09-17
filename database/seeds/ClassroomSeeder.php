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
        	'name'				=> 'XII TKJ',
        	'grade'				=> 12,
        	'group'				=> 'Teknik Komputer Jaringan',
        	'settings'			=> []
        ]);

        Classroom::create([
            'teacher_id'        => 3,
            'name'              => 'XII Ikom',
            'grade'             => 12,
            'group'             => 'Ilmu Komunikasi & Informasi',
            'settings'          => []
        ]);

        Classroom::create([
            'teacher_id'        => 4,
            'name'              => 'XI Bisnis',
            'grade'             => 11,
            'group'             => 'Binis & Pemasaran',
            'settings'          => []
        ]);
    }
}
