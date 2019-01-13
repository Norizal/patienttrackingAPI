<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientHUKM extends Model
{
     //Table name
   protected $table = 'patient_hukm';
   //Primary Key
   public $primaryKey = 'hukm_id';
   // Timestamps
   public $timeStamps = true;

     // Create relationship
     public function user(){
        return $this->belongsTo('App\User');
    }
}
