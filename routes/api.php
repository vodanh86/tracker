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
Route::post('/alert/create', 'AlertController@store');
Route::put('/alert/update/{id}', 'AlertController@update');
Route::patch('/alert/update/{id}', 'AlertController@update');
Route::get('/alert/get-by-id/{id}', 'AlertController@show');
Route::delete('/alert/delete/{id}', 'AlertController@destroy');
Route::get('/alert/all', 'AlertController@index');

//zones
Route::post('/zone/create', 'ZoneController@store');
Route::put('/zone/update/{id}', 'ZoneController@update');
Route::patch('/zone/update/{id}', 'ZoneController@update');
Route::get('/zone/get-by-id/{id}', 'ZoneController@show');
Route::delete('/zone/delete/{id}', 'ZoneController@destroy');
Route::get('/zone/get-all', 'ZoneController@getAllZones');
Route::get('/zone/all', 'ZoneController@index');

//history-location
Route::post('/history-location/create', 'HistoryLocationController@store');
Route::put('/history-location/update/{id}', 'HistoryLocationController@update');
Route::patch('/history-location/update/{id}', 'HistoryLocationController@update');
Route::get('/history-location/get-by-id/{id}', 'HistoryLocationController@show');
Route::delete('/history-location/delete/{id}', 'HistoryLocationController@destroy');
Route::get('/history-location/get-by-user/{id}', 'HistoryLocationController@getByUser');
Route::get('/history-location/all', 'HistoryLocationController@index');

//user-friend
Route::post('/user-friend/create', 'UserFriendController@store');
Route::put('/user-friend/update/{id}', 'UserFriendController@update');
Route::patch('/user-friend/update/{id}', 'UserFriendController@update');
Route::get('/user-friend/get-by-id/{id}', 'UserFriendController@show');
Route::delete('/user-friend/delete/{id}', 'UserFriendController@destroy');
Route::get('/user-friend/get-list-friend-by-user/{id}', 'UserFriendController@getListFriends');
Route::get('/user-friend/all', 'UserFriendController@index');

//share-code
Route::post('/share-code/create', 'ShareCodeController@store');
Route::put('/share-code/update/{id}', 'ShareCodeController@update');
Route::patch('/share-code/update/{id}', 'ShareCodeController@update');
Route::get('/share-code/get-by-id/{id}', 'ShareCodeController@show');
Route::delete('/share-code/delete/{id}', 'ShareCodeController@destroy');
Route::get('/share-code/all', 'ShareCodeController@index');
Route::get('/share-code/get-by-user/{id}', 'ShareCodeController@getByUser');