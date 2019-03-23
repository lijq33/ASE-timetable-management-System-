<?php

use Faker\Generator as Faker;

$factory->define(App\Timetable::class, function (Faker $faker) {
    return [
        'user_id' => '1',
        'subject' => $faker->title(),

        'start_date' => '2019/12/12',
        'end_date' => '2019/12/12',
        'start_time' => '12:00:00',
        'end_time' => '12:30:00',
        
        'is_all_day' => 0,
        'is_appointment' => false,
        'limited_to' => null,
        'description' => null,
        'no_of_slots' => null,
        'no_of_appointments' => 0,
        'recurrence_rule' => null,
        'location' => null,
        'price' => 0,

    ];
});

$factory->state(\App\Timetable::class, 'company_non_appointment', function ($faker) {
    return [
        'price' => '00.00',
        'location' => 'test',
        'is_appointment' => 0,
        'limited_to' => 'private',
        'no_of_slots' => 0,
        'no_of_appointments' => 0,
    ];
});

$factory->state(\App\Timetable::class, 'company_appointment_with_payment', function ($faker) {
    return [
        'price' => 5,
        'location' => 'test',
        'is_appointment' => 1,
        'limited_to' => 'public',
        'no_of_slots' => 2,
        'no_of_appointments' => 0,
    ];
});

$factory->state(\App\Timetable::class, 'company_appointment_without_payment', function ($faker) {
    return [
        'price' => 0,
        'location' => 'test',
        'is_appointment' => 1,
        'limited_to' => 'public',
        'no_of_slots' => 2,
        'no_of_appointments' => 0,
    ];
});
