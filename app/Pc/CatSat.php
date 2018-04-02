<?php

namespace App\Pc;

use Illuminate\Database\Eloquent\Model;

class CatSat extends Model {
  public $timestamps = false;
  protected $table = "pc_sat_cats";

  protected $fillable = [
    'sat_branch', 'subcat_id', 'cat_id'
  ];
}
