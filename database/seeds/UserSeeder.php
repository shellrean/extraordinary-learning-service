<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
        	'name'		=> 'Administrator',
        	'email'		=> 'admin@shellrean.com',
        	'password'	=> bcrypt('criticalpassword'),
        	'role'		=> '0',
        	'isactive'  => true,
        	'details'	=> []
        ]);
    }
}
