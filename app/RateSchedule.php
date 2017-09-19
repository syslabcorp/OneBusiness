<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateSchedule extends Model
{
  public $timestamps = false;
  protected $table = "t_rates_sched";
  protected $primaryKey = "ID";
  protected $connection = 'mysql2';

  protected $fillable = [
    'rate_date', 'tmplate_id', 'Branch'
  ];

  protected $dates = [
    'rate_date'
  ];

  #Relationships
  public function template() {
    return $this->belongsTo(\App\RateTemplate::class, 'tmplate_id', 'tmplate_id');
  }
}
