<?php

namespace App\Models\Spo;

use Illuminate\Database\Eloquent\Model;

class Hdr extends Model {
  public $timestamps = false;
  protected $table = "s_po_hdr";
  protected $primaryKey = "po_no";

  protected $fillable = [
    'po_date', 'tot_pcs', 'Prodserved_Line',
    'total_amt', 'po_tmpl8_id'
  ];

  public function template() {
    return $this->belongsTo(Tmpl8Hdr::class, 'po_tmpl8_id', 'po_tmpl8_id');
  }

  public function items() {
    return $this->hasMany(Detail::class, 'po_no', 'po_no');
  }
}
