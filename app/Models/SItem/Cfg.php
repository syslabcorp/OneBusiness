<?php

namespace App\Models\SItem;

use Illuminate\Database\Eloquent\Model;

class Cfg extends Model {
  public $timestamps = false;
  protected $table = "s_item_cfg";
  protected $primaryKey = "Branch";

  protected $fillable = [
  ];

  public function item() {
    return $this->belongsTo(\App\StockItem::class, 'item_id', 'item_id');
  }
}
