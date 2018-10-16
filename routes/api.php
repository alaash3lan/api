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

Route::middleware('auth:api')->get('/area', function (Request $request) {

    $response = file_get_contents('https://restcountries.eu/rest/v2/all');
   return $response = json_decode($response);

});

    Route::post('register', 'ApiUsersController@create');
    Route::post('login', 'ApiUsersController@login');
