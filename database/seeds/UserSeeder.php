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

        User::create([
            'name'      => 'Sefna Ardiana Nugraha .MM',
            'email'     => 'sefna@shellrean.com',
            'password'  => bcrypt('password'),
            'role'      => '1',
            'isactive'  => true,
            'details'   => []
        ]);

        User::create([
            'name'      => 'Aditiya Mahfad .Spd',
            'email'     => 'aditia@shellrean.com',
            'password'  => bcrypt('password'),
            'role'      => '1',
            'isactive'  => true,
            'details'   => []
        ]);

        User::create([
            'name'      => 'Rahmadila Setia .Spd',
            'email'     => 'ramadila@shellrean.com',
            'password'  => bcrypt('password'),
            'role'      => '1',
            'isactive'  => true,
            'details'   => []
        ]);

        User::create([
            'name'      => 'IBRAHIM SATYANEGARA NUGROHO',
            'email'     => 'ibrahim@shellrean.com',
            'password'  => bcrypt('password'),
            'role'      => '2',
            'isactive'  => true,
            'details'   => []
        ]);

        User::create([
            'name'      => 'INDRIA SAFITRI RETNO',
            'email'     => 'indria@shellrean.com',
            'password'  => bcrypt('password'),
            'role'      => '2',
            'isactive'  => true,
            'details'   => []
        ]);
    }
}
