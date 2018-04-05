<?php

namespace App\Pc;

use Illuminate\Database\Eloquent\Model;

class CatSat extends Model {
  public $timestamps = false;
  protected $table = "pc_sat_cats";

  protected $fillable = [
    'sat_branch', 'subcat_id', 'cat_id'
  ];

  protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query) {
    $query
    ->where('sat_branch', '=', $this->sat_branch)
    ->where('subcat_id', '=', $this->subcat_id);
    return $query;
  }
}
