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

/// Kin Part ////
Route::post('loginKin', 'API\KinUserController@login');
Route::post('registerKin', 'API\KinUserController@register');



//PPUKM Part////

Route::post('loginPPUKM', 'API\PPUKMUserController@login');
Route::post('registerPPUKM', 'API\PPUKMUserController@register');


Route::post('createPatientHUKM', 'API\PPUKMDummyCHeTController@createPatientHUKM');
Route::get('getPatientHUKMByID/{hukm_icnumber}', 'API\PPUKMDummyCHeTController@getPatientHUKMByID');
Route::get('getPatientHUKM', 'API\PPUKMDummyCHeTController@getPatientHUKM');

Route::post('createLocation', 'API\LocationController@createLocation');
Route::get('getLocation/{id}', 'API\LocationController@getLocationByID');
Route::get('getLocation', 'API\LocationController@getLocation');


Route::post('createBeacon', 'API\BeaconController@createBeacon');
Route::get('getBeacon/{id}', 'API\BeaconController@getBeaconByID');
Route::get('getBeacon', 'API\BeaconController@getBeacon');

Route::post('createMedicalStatus', 'API\MedicalStatusController@createMedicalStatus');
Route::get('getMedicalStatus/{id}', 'API\MedicalStatusController@getMedicalStatusByID');
Route::get('getMedicalStatus', 'API\MedicalStatusController@getMedicalStatus');




Route::group(['middleware' => 'auth:api'], function(){

Route::post('details', 'API\PPUKMUserController@details');
Route::post('createPatient', 'API\PPUKMPatientController@createPatient');
Route::get('getPatientPPUKM', 'API\PPUKMPatientController@getPatient');


//Kin Part /////
Route::post('createRelationship', 'API\KinUserController@createRelationship');
Route::get('getPatientKin', 'API\KinPatientController@getPatient');

Route::post('kinChat', 'API\KinCommunicationController@createChat');
Route::post('getkinChat', 'API\KinCommunicationController@getChat');
Route::post('updatestatuskinChat', 'API\KinCommunicationController@updateStatusChat');
Route::post('ppukmChat', 'API\PPUKMCommunicationController@createChat');
Route::post('getppukmChat', 'API\PPUKMCommunicationController@getChat');
Route::post('updatestatusppukmChat', 'API\PPUKMCommunicationController@updateStatusChat');



});
