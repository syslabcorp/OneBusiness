<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HCategory extends Model {
  public $timestamps = false;
  protected $table = "h_category";
  protected $primaryKey = "doc_no";

  protected $fillable = [
    'description', 'series', 'Deleted'
  ];

  public function docs() {
    return $this->hasMany(\App\HDocs::class, 'doc_no', 'doc_no');
  }

  public function subcategories() {
    return $this->hasMany(\App\HSubcategory::class, 'doc_no', 'doc_no');
  }
}
