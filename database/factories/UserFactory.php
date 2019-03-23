<?php

use Faker\Generator as Faker;

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