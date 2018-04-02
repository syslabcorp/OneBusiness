<?php

namespace App\Pc;

use Illuminate\Database\Eloquent\Model;

class Cat extends Model {
  public $timestamps = false;
  protected $table = "pc_cat";
  protected $primaryKey = "cat_id";

  protected $fillable = [
    'description', 'deleted', 'sat_branch', 'active'
  ];

  public function subcategories() {
    return $this->hasMany(\App\Pc\Subcat::class, 'cat_id', 'cat_id');
  }
}
