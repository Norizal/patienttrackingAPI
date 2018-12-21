<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beacon extends Model
{
     //Table name
   protected $table = 'beacon';
   //Primary Key
   public $primaryKey = 'id';
   // Timestamps
   public $timeStamps = true;

     // Create relationship
     public function user(){
        return $this->belongsTo('App\User');
    }
}
