<?php

namespace App\Models\Spo;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model {
  public $timestamps = false;
  protected $table = "s_po_detail";
  protected $primaryKey = "item_id";

  protected $fillable = [
    'ServedQty', 'Branch', 'po_no'
  ];

  public function rcvDetails() {
    return $this->hasMany(\App\Srcvdetail::class, 'item_id', 'item_id');
  }
}
