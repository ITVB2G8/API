<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherdataController;

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
Use app\Models;

//weatherdata routes
Route::get('weather', 'App\Http\Controllers\WeatherdataController@index');
Route::get('weather/{id}', 'App\Http\Controllers\WeatherdataController@show');
Route::get('weather/station/{station_name}', 'App\Http\Controllers\WeatherdataController@search');
Route::get('weather/{date}', 'App\Http\Controllers\WeatherdataController@searchDate');
Route::get('weather/{date}/{time}', 'App\Http\Controllers\WeatherdataController@searchTime');
Route::get('weather/station/{station_name}/{date}', 'App\Http\Controllers\WeatherdataController@searchStationDate');
Route::get('weather/station/{station_name}/{date}/{time}', 'App\Http\Controllers\WeatherdataController@searchStationDateTime');
Route::post('weather/getData', 'App\Http\Controllers\WeatherdataController@getData');
Route::post('weather', 'App\Http\Controllers\WeatherdataController@store');

Route::controller(App\Http\Controllers\UserController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group( function () {
    //protected routes
});
//TODO Get allowed stations
