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

Route::group([ 'prefix' => 'auth'], function ($router) {
    Route::post('register', 'RegisterController@store');
    Route::post('login', 'AuthController@login');
    
    Route::post('me', 'AuthController@me');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('logout', 'AuthController@logout');

});

Route::group(['middleware' => 'auth.user'], function ($router) {
    Route::get('appointment/get', 'AppointmentController@show');
    Route::post('appointment/new', 'AppointmentController@store');
    Route::post('appointment/update', 'AppointmentController@update');

    Route::post('appointment/delete', 'AppointmentController@destroy');
    Route::post('feedback/create', 'FeedbackController@store');

});

Route::group(['middleware' => 'auth.admin'], function ($router) {
    Route::get('feedback/show', 'FeedbackController@all');
});
