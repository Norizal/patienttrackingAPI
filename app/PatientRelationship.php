<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientRelationship extends Model
{
      //Table name
   protected $table = 'patient_relationship';
   //Primary Key
   public $primaryKey = 'patient_relationship_id';
   // Timestamps
   public $timeStamps = true;

     // Create relationship
     public function user(){
        return $this->belongsTo('App\User');
    }
}
