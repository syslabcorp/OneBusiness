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
    'CreatedAt', 'TellerID', 'Status', 'Subtotal'
  ];
  
  protected $dates = [
    'CreatedAt'
  ];

  public function details() {
    return $this->hasMany(\App\RemittanceDetail::class, "RemittanceCollectionID", "ID");
  }

  public function user() {
    return $this->belongsTo(\App\User::class, "TellerID", "UserID");
  }

  public function detail($groupId, $branchId) {
    $detail = $this->details()
                   ->where('Group', '=', $groupId)
                   ->where('Branch', '=', $branchId)
                   ->first();
    if(!$detail) {
      $detail = new \App\RemittanceDetail;
      $detail->Group = $groupId;
      $detail->Branch = $branchId;
    }
    return $detail;
  }

  public function getStartCRR($groupId, $branchId) {
    $startCRR = 1;

    $detail = $this->details()->where('Group', '=', $groupId)
                              ->where('Branch', '=', $branchId)
                              ->first();
    if($detail) {
      $startCRR = $detail->Start_CRR;
    }else {
      $remittance = $this->details()->orderBy('End_CRR', 'DESC')->first();
      if($remittance) {
        $startCRR = $remittance->End_CRR + 1;
      }
    }
    return $startCRR;
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function($collection) {
      $collection->details()->delete();
    });
  }
}
