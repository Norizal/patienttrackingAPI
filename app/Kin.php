<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kin extends Model
{
   //Table name
   protected $table = 'kin';
   //Primary Key
   public $primaryKey = 'id';
   // Timestamps
   protected $fillable = [
      'name',
      'icnumber',
      'email',
      'gender',
      'race',
      'phonenumber',
      'password'
    ];
}
