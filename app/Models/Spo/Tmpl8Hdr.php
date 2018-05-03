<?php

namespace App\Models\Spo;

use Illuminate\Database\Eloquent\Model;

class Tmpl8Hdr extends Model {
  public $timestamps = false;
  protected $table = "s_po_tmpl8_hdr";
  protected $primaryKey = "po_tmpl8_id";

  protected $fillable = [
    'po_tmpl8_desc', 'city_id', 'po_avg_cycle',
    'active'
  ];
}
