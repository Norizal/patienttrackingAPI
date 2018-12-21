<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
     //Table name
   protected $table = 'location';
   //Primary Key
   public $primaryKey = 'id';


   // Timestamps
   public $timeStamps = true;

     // Create relationship
     public function user(){
        return $this->belongsTo('App\User');
    }
}
