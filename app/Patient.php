<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    //Table name
    protected $table = 'patient';
    //Primary Key
    public $primaryKey = 'id';
    // Timestamps
    public $timeStamps = true;
 
      // Create relationship
      public function user(){
         return $this->belongsTo('App\User');
     }
}
