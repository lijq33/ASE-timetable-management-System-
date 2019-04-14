<?php

use Faker\Generator as Faker;

<<<<<<< HEAD
=======
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


/**
* A method used to generate a random string of name, email and remember_token
* However, password will be fixed
*
* @return name, email, password and remember_token
*/
>>>>>>> develop
$factory->define(App\User::class, function (Faker $faker) {
    
    return [
        'userable_id' => '1',
    ];
});

$factory->state(\App\User::class, 'individual', function ($faker) {
    return [
        'userable_type' => 'App\Individual'
    ];
});

$factory->state(\App\User::class, 'company', function ($faker) {
    return [
        'userable_type' => 'App\Company'
    ];
});