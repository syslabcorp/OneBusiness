<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remittance extends Model
{
  public $timestamps = false;
  protected $table = "t_remitance";
  protected $primaryKey = "txn_id";

  protected $fillable = [
    'TotalRemit', 'TotalSales', 'Net_TotalSales',
    'Games_TotalSales', 'Sales_TotalSales', 'Serv_TotalSales',
    'Branch', 'Shift_ID', 'Net_Chercker', 'Sales_Checker',
    'Wrong_Input', 'Adj_Short', 'Adj_Amt', 'memo_txn_no', 'Notes'
  ];

  public function shift() {
    return $this->belongsTo(\App\Shift::class, 'Shift_ID', 'Shift_ID');
  }

  public function branch()
  {
    return $this->belongsTo(\App\Branch::class, "Branch", "Branch");
  }

}
