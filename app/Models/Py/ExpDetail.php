<?php

namespace App\Models\Py;

use Illuminate\Database\Eloquent\Model;

class ExpDetail extends Model {
  public $timestamps = false;
  protected $table = "py_exp_detail";

  protected $fillable = [
    'range_1', 'range_2', 'emp_share', 'empr_share'
  ];
}
