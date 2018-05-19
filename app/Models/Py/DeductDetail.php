<?php

namespace App\Models\Py;

use Illuminate\Database\Eloquent\Model;

class DeductDetail extends Model {
  public $timestamps = false;
  protected $table = "py_deduct_detail";

  protected $fillable = [
    'range1', 'range2', 'multi'
  ];
}
