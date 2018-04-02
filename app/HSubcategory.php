<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HSubcategory extends Model {
  public $timestamps = false;
  protected $table = "h_subcategory";
  protected $primaryKey = "subcat_id";

  protected $fillable = [
    'description', 'expires', 'Deleted', 'multi_doc'
  ];

  public function docs() {
    return $this->hasMany(\App\HDocs::class, 'doc_no', 'doc_no');
  }

  public function category() {
    return $this->belongsTo(\App\HCategory::class, 'doc_no', 'doc_no');
  }
}
