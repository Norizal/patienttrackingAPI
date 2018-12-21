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


Route::post('createPatientHUKM', 'API\PPUKMDummyCHeTController@createPatientHUKM');
Route::get('getPatientHUKM/{id}', 'API\PPUKMDummyCHeTController@getPatientHUKM');

Route::post('createLocation', 'API\LocationController@createLocation');
Route::get('getLocation/{id}', 'API\LocationController@getLocation');


Route::post('createBeacon', 'API\BeaconController@createBeacon');
Route::get('getBeacon/{id}', 'API\BeaconController@getBeacon');

Route::post('createMedicalStatus', 'API\MedicalStatusController@createMedicalStatus');
Route::get('getMedicalStatus/{id}', 'API\MedicalStatusController@getMedicalStatus');




Route::group(['middleware' => 'auth:api'], function(){

Route::post('details', 'API\PPUKMUserController@details');
Route::get('logout', 'API\UserController@logout')->name('logout');
Route::post('createPatient', 'API\PPUKMPatientController@createPatient');

});
