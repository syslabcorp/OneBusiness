<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockType extends Model
{
  public $timestamps = false;
  protected $table = "s_invtry_type";
  protected $primaryKey = "inv_type";
  protected $connection = 'mysql';
  
  protected $fillable = [

  ];

}
