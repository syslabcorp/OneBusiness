<?php

namespace App\Pc;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model {
  public $timestamps = false;
  protected $table = "pc_branches";
  protected $primaryKey = "sat_branch";

  protected $fillable = [
    'description', 'short_name', 'active', 'notes'
  ];
}
