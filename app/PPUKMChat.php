<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PPUKMChat extends Model
{
     //Table name
   protected $table = 'ppukm_chat';
   //Primary Key
   public $primaryKey = 'ppukm_chat_id';

     // Create relationship
     public function user(){
      return $this->belongsTo('App\User');
  }

}
