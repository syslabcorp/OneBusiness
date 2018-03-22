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

  protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
  {
    $query
    ->where('Serv_ID', '=', $this->Serv_ID)
    ->where('Branch', '=', $this->Branch);
    return $query;
  }
}
