<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
     //Table name
   protected $table = 'device';
   //Primary Key
   public $primaryKey = 'device_id';

     // Create relationship
     public function user(){
      return $this->belongsTo('App\User');
  }
}
