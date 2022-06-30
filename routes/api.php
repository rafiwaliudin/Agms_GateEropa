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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'notification', 'as' => 'api.notification.'], function () {
    Route::post('add', ['as' => 'add', 'uses' => 'API\NotificationController@add']);
    Route::get('list', ['as' => 'list', 'uses' => 'API\NotificationController@list']);
    Route::get('detail', ['as' => 'detail', 'uses' => 'API\NotificationController@detail']);
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('login', 'API\AuthenticationController@login');
    Route::post('logout', 'API\AuthenticationController@logout');
    Route::post('refresh', 'API\AuthenticationController@refresh');
});


Route::group(['middleware' => ['api']], function () {
    Route::group(['prefix' => 'gate', 'as' => 'gate'], function () {
        Route::get('open-gate', ['as' => 'open-gate', 'uses' => 'API\GateController@openGate']);
    });
});
