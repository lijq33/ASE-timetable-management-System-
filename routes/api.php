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

Route::group([ 'prefix' => 'api:auth'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::group(['middleware' => 'auth'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::get('feedback/show', 'FeedbackController@all');
    // Route::get('customers', 'CustomersController@all');
    // Route::get('customers/{id}', 'CustomersController@get');
    Route::post('feedback/create', 'FeedbackController@store');

});
Route::group(['prefix' => 'auth'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

Route::group(['prefix' => 'api'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::get('feedback/show', 'FeedbackController@all');
    // Route::get('customers', 'CustomersController@all');
    // Route::get('customers/{id}', 'CustomersController@get');
    Route::post('feedback/create', 'FeedbackController@store');

});