<?php

namespace App\Models\T;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Shift extends Model
{
  public $timestamps = false;
  protected $table = "t_shifts";
  protected $primaryKey = "Shift_ID";

  protected $fillable = [
  ];

  protected $dates = [
    'ShiftDate'
  ];

  public function remittance() {
    return $this->belongsTo(\App\Remittance::class, 'Shift_ID', 'Shift_ID');
  }

  public function user()
  {
    return $this->belongsTo(\App\User::class, 'ShiftOwner' , 'UserID');
  }

  public function branch()
  {
    return $this->belongsTo(\App\Branch::class, 'Branch' , 'Branch');
  }
}
