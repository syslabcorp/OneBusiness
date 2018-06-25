<?php

namespace App\Models\Py;

use Illuminate\Database\Eloquent\Model;

class ExpMstr extends Model {
  public $timestamps = false;
  protected $table = "py_exp_mstr";
  protected $primaryKey = "ID_exp";

  protected $fillable = [
    'description', 'type', 'fixed_amt', 'total_amt', 'period', 'incl_gross',
    'active', 'category'
  ];

  public function details()
  {
    return $this->hasMany(ExpDetail::class, 'ID_exp', 'ID_exp');
  }
}
