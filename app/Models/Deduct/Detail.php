<?php

namespace App\Models\Deduct;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model {
  public $timestamps = false;
  protected $table = "deduct_detail";

  protected $fillable = [
    'range1', 'range2', 'multi'
  ];
}
