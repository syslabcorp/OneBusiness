<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateTemplate extends Model
{
  public $timestamps = false;
  protected $table = "t_rates_hdr";
  protected $primaryKey = "tmplate_id";
  protected $connection = 'mysql2';

  protected $fillable = [
    'tmplate_name', 'charge_mode', 'ZoneStart1', 'ZoneStart2', 'ZoneStart3', 'DiscStubPrint', "DiscStubMsg",
    'DiscValidity', 'Discount1', 'Discount2', 'Discount3', 'MinimumChrg', 'MinimumTime',
    'Modified', 'Branch', 'Color'
  ];


  // Relationships
  public function details() {
    return $this->hasMany(\App\RateDetail::class, 'tmplate_id', 'tmplate_id');
  }
}
