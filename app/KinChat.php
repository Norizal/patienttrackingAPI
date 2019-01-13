<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KinChat extends Model
{
    //Table name
    protected $table = 'kin_chat';
    //Primary Key
    public $primaryKey = 'kin_chat_id';
 
      // Create relationship
      public function user(){
       return $this->belongsTo('App\User');
   }
 
}
