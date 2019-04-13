<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable;
/**
* The attributes that are mass assignable.
*
* @var array
*/
protected $fillable = [
'name', 'email', 'password',
];
/**
* The attributes that should be hidden for arrays.
*
* @var array
*/
protected $hidden = [
'password', 'remember_token',
];

 // Create relationship
 public function ppukm(){
  return $this->hasMany('App\PPUKM');
}

 // Create relationship
 public function kin(){
  return $this->hasMany('App\Kin');
}

 // Create relationship
 public function device(){
  return $this->hasMany('App\Device');
}

 // Create relationship
 public function patientrelationship(){
  return $this->hasMany('App\PatientRelationship');
}


// Create relationship
public function patient(){
  return $this->hasMany('App\Patient');
}

// Create relationship
public function patient_hukm(){
  return $this->hasMany('App\PatientHUKM');
}

// Create relationship
public function medicalstatus(){
  return $this->hasMany('App\MedicalStatus');

}







}