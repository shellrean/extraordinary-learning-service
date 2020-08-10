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
        	'description' => 'Bahasa nusantara',
        	'settings' => []
        ]);
    }
}
