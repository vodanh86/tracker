<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('users', UserController::class);

//user
Route::get('/user/get-other-user', 'UserController@getOtherUsers');
Route::get('/user-list/get-remain-user', 'UserController@getRemainUsers');

//alerts
Route::get('/alert/get-by-user/{id}', 'AlertController@getByUser');

//zones
Route::post('/zone/create', 'ZoneController@store');
Route::put('/zone/update/{id}', 'ZoneController@update');
Route::patch('/zone/update/{id}', 'ZoneController@update');
Route::get('/zone/get-by-id/{id}', 'ZoneController@show');
Route::delete('/zone/delete/{id}', 'ZoneController@destroy');
Route::get('/zone/get-all', 'ZoneController@getAllZones');
Route::get('/zone/all', 'ZoneController@index');