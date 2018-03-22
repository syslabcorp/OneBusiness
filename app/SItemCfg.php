<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SItemCfg extends Model {
  protected $table = "s_item_cfg";
  protected $primaryKey = "Branch";
  public $timestamps = false;
  protected $connection = 'mysql';

  protected $fillable = [
    'Branch', 'item_id', 'ItemCode', 'Sell_Price', 'Min_Level', 'Active',
    'pts_price', 'pts_redeemable'
  ];

  protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query) {
    $query
    ->where('item_id', '=', $this->item_id)
    ->where('Branch', '=', $this->Branch);
    return $query;
  }
}
