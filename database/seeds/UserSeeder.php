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
            'isonline'  => true,
        	'details'	=> []
        ]);

        User::create([
            'name'      => 'Sefna',
            'email'     => 'sefna@shellrean.com',
            'password'  => bcrypt('password'),
            'role'      => '1',
            'isactive'  => true,
            'isonline'  => true,
            'details'   => []
        ]);

        User::create([
            'name'      => 'Brian',
            'email'     => 'brian@shellrean.com',
            'password'  => bcrypt('password'),
            'role'      => '2',
            'isactive'  => true,
            'isonline'  => true,
            'details'   => []
        ]);
    }
}
