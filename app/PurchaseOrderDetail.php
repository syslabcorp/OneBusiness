<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
  public $timestamps = false;
  protected $table = "s_po_detail";
  // protected $connection = 'mysql2';

  protected $fillable = [
    'po_no', 'Branch', 'item_id', 'ItemCode',
    'Qty', 'ServedQty', 'cost'
  ];

}
