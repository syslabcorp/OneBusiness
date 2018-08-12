<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HDocs extends Model
{
  public $timestamps = false;
  protected $table = "h_docs";
  protected $primaryKey = "txn_no";

  protected $fillable = [
    'description', 'series_no', 'branch', 'img_file', 'notes', 'doc_exp',
    'subcat_id', 'doc_no', 'emp_id', 'doc_date'
  ];

  public function user() {
    return $this->belongsTo(\App\User::class, 'UserID', 'emp_id');
  }

  public function category() {
    return $this->belongsTo(\App\HCategory::class, 'doc_no', 'doc_no');
  }

  public function subcategory() {
    return $this->belongsTo(\App\HSubcategory::class, 'subcat_id', 'subcat_id');
  }

  public function branch()
  {
      return $this->belongsTo(\App\Branch::class, 'branch' , 'Branch');
  }

  public function getBranchIdAttribute($value)
  {
    dd($value);
      return ucfirst($value);
  }
}
