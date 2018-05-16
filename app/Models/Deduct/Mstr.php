<?php

namespace App\Models\Deduct;

use Illuminate\Database\Eloquent\Model;

class Mstr extends Model {
  public $timestamps = false;
  protected $table = "deduct_mstr";
  protected $primaryKey = "ID_deduct";

  protected $fillable = [
    'description', 'type', 'fixed_amt', 'total_amt', 'period', 'incl_gross',
    'active', 'category'
  ];

  public function details()
  {
    return $this->hasMany(Detail::class, 'id_deduct', 'ID_deduct');
  }
}
