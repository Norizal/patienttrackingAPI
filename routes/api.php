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

Route::post('loginPPUKM', 'API\PPUKMUserController@login');
Route::post('registerPPUKM', 'API\PPUKMUserController@register');
Route::group(['middleware' => 'auth:api'], function(){
Route::post('details', 'API\PPUKMUserController@details');
Route::get('logout', 'API\UserController@logout')->name('logout');
});
