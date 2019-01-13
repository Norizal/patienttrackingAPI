<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalStatus extends Model
{
     //Table name
   protected $table = 'medical_status';
   //Primary Key
   public $primaryKey = 'medical_status_id';
   // Timestamps
   public $timeStamps = true;

     // Create relationship
     public function user(){
        return $this->belongsTo('App\User');
    }
}
