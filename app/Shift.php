<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

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

  public function user()
  {
    return $this->belongsTo(\App\User::class, 'ShiftOwner' , 'UserID');
  }

  public function branch()
  {
    return $this->belongsTo(\App\Branch::class, 'Branch' , 'Branch');
  }

  public function getTime(){
    $date=date_create_from_format("H:i:s","03:23:23");
    return $date->format('H:i a');
  }
}
