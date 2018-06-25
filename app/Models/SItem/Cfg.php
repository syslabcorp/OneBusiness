<?php

namespace App\Models\SItem;

use Illuminate\Database\Eloquent\Model;

class Cfg extends Model {
  public $timestamps = false;
  protected $table = "s_item_cfg";
  protected $primaryKey = "Branch";

  protected $fillable = [
    'Branch', 'item_id', 'ItemCode', 'Sell_Price', 'Min_Level', 'Active',
    'pts_price', 'pts_redeemable'
  ];

  protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query) {
    $query->where('item_id', '=', $this->item_id)
          ->where('Branch', '=', $this->Branch);

    return $query;
  }

  public function item() {
    return $this->belongsTo(\App\StockItem::class, 'item_id', 'item_id');
  }
}
