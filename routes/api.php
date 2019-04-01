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

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('logout', 'Api\AuthController@logout');
    Route::resource('invite', 'Api\FriendsController');
    Route::post('friend/delete', 'Api\FriendsController@deleteFriend');
    Route::get('friend/request', 'Api\FriendsController@getRequestList');
    Route::post('friend/accept', 'Api\FriendsController@acceptRequest');

});

Route::get('confirmemail', 'Api\AuthController@confirmEmail');
Route::post('login', 'Api\AuthController@login');
Route::post('register', 'Api\AuthController@register');