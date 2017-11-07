<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomStatus extends Model
{
  public $timestamps = false;
  protected $table = "roomstatus";
  protected $primaryKey = "RmIndex";
  protected $connection = 'k_master';

  protected $fillable = [
    'RmIndex', 'RmTag', 'Branch', 'last_updated_by'
  ];

  protected $dates = [
    'last_update'
  ];

  protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
  {
      $query
      ->where('Branch', '=', $this->Branch)
      ->where('RmIndex', '=', $this->RmIndex);
      return $query;
  }

  public function updatedBy() {
    return $this->belongsTo(\App\User::class, 'last_updated_by', 'UserID');
  }
}
