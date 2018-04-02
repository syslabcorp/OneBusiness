<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
  public $timestamps = false;
  protected $table = "s_detail";
  // protected $primaryKey = "Sales_ID";

  public function sale() {
    return $this->belongsTo(\App\Sale::class, 'Sales_ID', 'Sales_ID');
  }
}
