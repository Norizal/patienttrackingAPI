<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PPUKM extends Model
{
   //Table name
   protected $table = 'ppukm';
   //Primary Key
   public $primaryKey = 'id';
  

     // Create relationship
     public function user(){
        return $this->belongsTo('App\User');
    }
    
}
