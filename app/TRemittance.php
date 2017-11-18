<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TRemittance extends Model
{
  public $timestamps = false;
  protected $table = "t_remitance";
  protected $primaryKey = "txn_id";
  protected $connection = 'mysql2';

  protected $fillable = [
      
  ];
}
