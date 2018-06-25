<?php

namespace App\Pc;

use Illuminate\Database\Eloquent\Model;

class Subcat extends Model {
  public $timestamps = false;
  protected $table = "pc_subcat";
  protected $primaryKey = "subcat_id";

  protected $fillable = [
    'description', 'deleted', 'active', 'cat_id'
  ];

  public function branches() {
    return $this->hasMany(\App\Pc\CatSat::class, 'subcat_id', 'subcat_id');
  }

  public function category() {
    return $this->belongsTo(\App\Pc\Cat::class, 'cat_id', 'cat_id');
  }
}
