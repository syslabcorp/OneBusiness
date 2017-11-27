<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Branch;

class RemittanceCollection extends Model
{
  public $timestamps = false;
  protected $table = "remittance_collections";
  protected $primaryKey = "ID";

  protected $fillable = [
    'Branch', 'Start_CRR', 'End_CRR', 'Total_Collection', 'Group', 'Time_Create', 'UserID'
  ];
  
  protected $dates = [
    'Time_Create'
  ];

  public function branch()
  {
    return $this->belongsTo(\App\Branch::class, "Branch", "Branch");
  }

  public function user()
  {
    return $this->belongsTo(\App\User::class, "UserID", "UserID");
  }
}
