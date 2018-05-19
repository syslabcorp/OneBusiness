<?php

namespace App\Models\Py;

use Illuminate\Database\Eloquent\Model;

class DeductMstr extends Model {
  public $timestamps = false;
  protected $table = "py_deduct_mstr";
  protected $primaryKey = "ID_deduct";

  protected $fillable = [
    'description', 'type', 'fixed_amt', 'total_amt', 'period', 'incl_gross',
    'active', 'category'
  ];

  public function details()
  {
    return $this->hasMany(DeductDetail::class, 'id_deduct', 'ID_deduct');
  }
}
