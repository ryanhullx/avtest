<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Auth::routes();

Route::get('/', 'CityController@index');
Route::get('/data/cities', 'CityController@cityData');
Route::get('/alter/{id}/{value}', 'CityController@alterStatus');


Route::get('/city/{city}/', 'StationController@index');
Route::get('/city/{city}/station/{id}/{day?}/{hour?}', 'StationController@stationShow');
Route::post('/city/{city}/station/{id}/predict', 'StationController@stationPredict');
Route::get('/data/stations/{city}', 'StationController@stationData');



Route::post('/journey/plan', 'JourneyController@journyPlanner');
Route::get('/journey/{from}/{to}/{day}/{hour}', 'JourneyController@jounryPlanView');

