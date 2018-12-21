<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kin extends Model
{
   //Table name
   protected $table = 'kin';
   //Primary Key
   public $primaryKey = 'id';

     // Create relationship
     public function user(){
      return $this->belongsTo('App\User');
  }

}
