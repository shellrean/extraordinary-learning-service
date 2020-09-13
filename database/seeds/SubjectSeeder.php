<?php

use Illuminate\Database\Seeder;
use App\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Subject::create([
        	'name'		=> 'Bahasa Indonesia',
        	'settings' => []
        ]);

        Subject::create([
            'name'      => 'Matematika',
            'settings'  => []
        ]);

        Subject::create([
            'name'      => 'Fisika',
            'settings'  => []
        ]);

        Subject::create([
            'name'      => 'Kimia',
            'settings'  => []
        ]);

        Subject::create([
            'name'      => 'Jaringan Advance',
            'settings'  => []
        ]);

        Subject::create([
            'name'      => 'Multimedia dan komunikasi',
            'settings'  => []
        ]);
    }
}
