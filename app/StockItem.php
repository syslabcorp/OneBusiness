<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
  public $timestamps = false;
  protected $table = "s_invtry_hdr";
  protected $primaryKey = "item_id";
  protected $connection = 'mysql';
  
  protected $fillable = [
    'itemCode', 'Brand_ID', 'Prod_Line',
    'Description', 'Unit', 'Packaging',
    'Threshold', 'Multiplier', 'Type', 'Min_Level',
    'Active', 'LastCost', 'barCode', 'TrackThis', 'Print_This',
    'UserID'
  ];

  public function product_line() {
    return $this->belongsTo(\App\ProductLine::class, "Prod_Line", "ProdLine_ID");
  }

  public function brand() {
    return $this->belongsTo(\App\Brand::class, "Brand_ID", "Brand_ID");
  }
}
