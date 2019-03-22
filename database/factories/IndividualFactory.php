<?php

use Faker\Generator as Faker;

$factory->define(App\Individual::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'nric' => 'S1234566Z',         
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123123'), // secret
        'telephone_number' => '12341234', 
        'remember_token' => str_random(10),
    ];
});
