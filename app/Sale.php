<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
  public $timestamps = false;
  protected $table = "s_hdr";
  protected $primaryKey = "Sales_ID";

  protected $dates = [
    'DateSold'
  ];

}
