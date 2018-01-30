<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class KShift extends Model
{
  public $timestamps = false;
  protected $table = "shifts";
  protected $primaryKey = "PrimaryKey";

  protected $fillable = [
  ];

  protected $dates = [
    'shift_start'
  ];

  public function remittance() {
    return $this->belongsTo(\App\KRemittance::class, 'Shift_ID', 'Shift_ID');
  }

  public function user()
  {
    return $this->belongsTo(\App\User::class, 'user_id' , 'UserID');
  }

  public function branch()
  {
    return $this->belongsTo(\App\Branch::class, 'Branch' , 'Branch');
  }

  public function getShiftDateAttribute() {
    return $this->shift_start;
  }
}
