<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KRateTemplate extends Model
{
  public $timestamps = false;
  protected $table = "t_rates_hdr";
  protected $primaryKey = "tmplate_id";
  protected $connection = 'k_master';

  protected $fillable = [
    'tmplate_name', 'charge_mode', 'ZoneStart1', 'ZoneStart2', 'ZoneStart3', 'DiscStubPrint', "DiscStubMsg",
    'DiscValidity', 'Discount1', 'Discount2', 'Discount3', 'MinimumChrg', 'MinimumTime',
    'Modified', 'Branch', 'active'
  ];


  // Relationships
  public function details() {
    return $this->hasMany(\App\KRateDetail::class, 'tmplate_id', 'tmplate_id');
  }

}
