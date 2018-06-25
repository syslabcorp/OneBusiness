<?php

namespace App\Models\Py;

use Illuminate\Database\Eloquent\Model;

class BenfDetail extends Model {
  public $timestamps = false;
  protected $table = "py_benf_detail";

  protected $fillable = [
    'range_1', 'range_2', 'emp_share'
  ];
}
