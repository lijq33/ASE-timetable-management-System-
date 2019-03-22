<?php

use Faker\Generator as Faker;


$factory->define(App\Company::class, function (Faker $faker) {
    return [
        'company_name' => $faker->name,
        'company_type' => 'Public company',
        'industry_type' => 'Accounting',
        'website' => null,
        'tagline' => null,
        'logo' => null,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('123123'), 
        'telephone_number' => '12341234', 
        'remember_token' => str_random(10),
    ];
});

