<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
  public $timestamps = false;
  protected $table = "t_shifts";
  protected $primaryKey = "Shift_ID";
  protected $connection = "mysql2";

  protected $fillable = [
      
  ];

  protected $dates = [
    'ShiftDate', 'ShiftTime'
  ];

  public function remittance() {
    return $this->belongsTo(\App\TRemittance::class, 'Shift_ID', 'Shift_ID');
  }
}
