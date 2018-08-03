<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HDocs extends Model
{
  public $timestamps = false;
  protected $table = "h_docs";
  protected $primaryKey = "txt_no";

  protected $fillable = [
    'description', 'series'
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
}
