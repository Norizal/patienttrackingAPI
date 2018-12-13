<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PPUKM extends Model
{
   //Table name
   protected $table = 'ppukm';
   //Primary Key
   public $primaryKey = 'id';
   // Timestamps
   protected $fillable = [
      'name',
      'staffid',
      'email',
      'gender',
      'race',
      'phonenumber',
      'password'
    ];

     // Create relationship
     public function user(){
        return $this->belongsTo('App\User');
    }
    
}
