<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateTemplate extends Model
{
  public $timestamps = false;
  protected $table = "t_rates_hdr";
  protected $primaryKey = "template_id";

  protected $fillable = [
    'template_name', 'charge_mode', 'ZoneStart1', 'ZoneStart2', 'ZoneStart3', 'DiscStubPrint', "DiscStubMsg",
    'DiscValidity', 'Discount1', 'Discount2', 'Discount3', 'MinimumChrg', 'MinimumTime',
    'Modified', 'Branch', 'Color'
  ];


  // Relationships
  public function details() {
    return $this->hasMany(\App\RateDetail::class, 'template_id', 'template_id');
  }
}
