<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HSubcategory extends Model {
  public $timestamps = false;
  protected $table = "h_subcategory";
  protected $primaryKey = "subcat_id";

  protected $fillable = [
    'description', 'expires', 'Deleted', 'mutli_doc'
  ];

  public function docs() {
    return $this->hasMany(\App\HDocs::class, 'doc_no', 'doc_no');
  }

  public function subcategories() {
    return $this->hasMany(\App\HSubcategory::class, 'doc_no', 'doc_no');
  }
}
