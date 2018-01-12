<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockDetail extends Model
{
  public $timestamps = false;
  protected $table = "s_rcv_detail";
  protected $primaryKey = "Movement_ID";
  protected $connection = 'mysql2';
  protected $dates = [
    'RcvDate'
  ];

  protected $fillable = [
    'RRNo', 'RcvDate', 'item_id',
    'itemCode', 'ServerQty', 'Qty',
    'Bal', 'Cost', 'RMA_Qty'
  ];

  // public function details() {
  //   return $this->hasMany(\App\RemittanceDetail::class, "RemittanceCollectionID", "ID");
  // }

  public function stock_item() {
    return $this->belongsTo(\App\StockItem::class, "item_id", "item_id");
  }

}
