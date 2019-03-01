<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('register', 'RegisterController@store');
    Route::post('login', 'AuthController@login');
    
    Route::post('me', 'AuthController@me');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('logout', 'AuthController@logout');

});

Route::group(['middleware' => 'auth.user'], function ($router) {
    Route::resource('timetable', 'TimetableController', ['except' => ['create', 'edit']]);
    Route::resource('appointment', 'AppointmentController', ['except' => ['create', 'edit', 'update']]);
    // Route::get('timetable/get', 'TimetableController@show');

    // Route::post('timetable/update', 'TimetableController@update');
    // Route::post('timetable/delete', 'TimetableController@destroy');
    
    // Route::get('appointment/get', 'AppointmentController@show');
    // Route::post('appointment', 'AppointmentController@store');
    // Route::post('appointment/update', 'AppointmentController@update');
    // Route::post('appointment/delete', 'AppointmentController@destroy');
});


Route::group(['middleware' => 'auth.individual'], function ($router) {

    // Route::get('timetable/get', 'TimetableController@show');
    // Route::post('timetable', 'TimetableController@store');
    // Route::post('timetable/update', 'TimetableController@update');
    // Route::post('timetable/delete', 'TimetableController@destroy');
    
    // Route::get('appointment/get', 'AppointmentController@show');
    // // Route::post('appointment', 'AppointmentController@store');
    // Route::post('appointment/update', 'AppointmentController@update');
    // Route::post('appointment/delete', 'AppointmentController@destroy');

});

Route::group(['middleware' => 'auth.company'], function ($router) {
    // Route::post('appointment', 'AppointmentController@store');

});
