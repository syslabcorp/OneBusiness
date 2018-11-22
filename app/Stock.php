<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use app\Vendor;

class Stock extends Model
{
  public $timestamps = false;
  protected $table = "s_rcv_hdr";
  protected $primaryKey = "txn_no";
  protected $dates = [
    'RcvDate',
    'DateSaved'
  ];

  protected $fillable = [
    'RRNo', 'RcvDate', 'TotalAmt',
    'Payment_ID', 'Supp_ID', 'DateSaved', 'Rcvd_By'
  ];

  public function stock_details() {
    return $this->hasMany(\App\StockDetail::class, "RR_No", "RR_No");
  }

  public function vendor() {
    return $this->belongsTo(\App\Vendor::class, "Supp_ID", "Supp_ID");
  }

  public function check_transfered()
  {
    foreach ($this->stock_details as $detail )
    {
      if($detail->Qty != $detail->Bal) 
      {
        return true; 
      }
    }

    return false;
  }

  public function total_amount()
  {
    $total = 0;
    foreach($this->stock_details as $stock_detail )
    {
      $total += $stock_detail->Cost * $stock_detail->Qty;
    }
    return $total;
  }
}
