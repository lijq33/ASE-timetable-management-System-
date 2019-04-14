<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndividualsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('individuals')->insert([
            'name' => 'Tee Wan Yi',
            'nric' => 'S1234566Z',
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('12345!qW'),
            'telephone_number' => random_int(90000000 ,99999999),
        ]);

        DB::table('individuals')->insert([
            'name' => 'Lee Jian Hao',
            'nric' => 'S1234567Z',
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('12345!qW'),
            'telephone_number' => random_int(90000000 ,99999999),
        ]);

        DB::table('individuals')->insert([
            'name' => 'Alicia',
            'nric' => 'S1234569Z',
            'email' => Str::random(10).'@gmail.com',
            'password' => bcrypt('12345!qW'),
            'telephone_number' => random_int(90000000 ,99999999),
        ]);
    }
}