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
    'Branch', 'Start_CRR', 'End_CRR', 'Total_Collection', 'Group'
  ];

  public function branch()
  {
    return $this->belongsTo(\App\Branch::class, "Branch", "Branch");
  }
}
