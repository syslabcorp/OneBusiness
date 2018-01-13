<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
  public $timestamps = false;
  protected $table = "s_po_hdr";
  protected $primaryKey = "po_no";
  protected $connection = 'mysql2';
  protected $dates = [
    'po_date',
  ];

  protected $fillable = [
    'po_no', 'po_date', 'tot_pcs',
    'served', 'total_amt'
  ];

  public function stock_details() {
    return $this->hasMany(\App\StockDetail::class, "RR_No", "RR_No");
  }

  public function vendor() {
    return $this->belongsTo(\App\Vendor::class, "Supp_ID", "Supp_ID");
  }

  

}
