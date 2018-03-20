<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SrvItemCfg extends Model {
  protected $table = "srv_item_cfg";
  protected $primaryKey = "Serv_ID";
  public $timestamps = false;

  protected $fillable = [
    'Amount', 'Active', 'Branch', 'Serv_ID'
  ];
}
