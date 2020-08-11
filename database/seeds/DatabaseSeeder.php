<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(ClassroomSeeder::class);
        $this->call(ClassroomSubjectSeeder::class);
        $this->call(TeacherSubjectSeeder::class);
    }
}
