<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KRemittance extends Model
{
  public $timestamps = false;
  protected $table = "remittance";
  protected $primaryKey = "txn_index";

  protected $fillable = [
    'TotalRemit', 'TotalSales', 'Net_TotalSales',
    'Games_TotalSales', 'Sales_TotalSales', 'Serv_TotalSales',
    'Branch', 'Shift_ID', 'Net_Chercker', 'Sales_Checker',
    'Wrong_Input', 'Adj_Short', 'Adj_Amt', 'memo_txn_no', 'Notes',
    'Sales_Checked'
  ];

  public function shift() {
    return $this->belongsTo(\App\KShift::class, 'Shift_ID', 'Shift_ID');
  }

  public function branch()
  {
    return $this->belongsTo(\App\Branch::class, "Branch", "Branch");
  }

}
