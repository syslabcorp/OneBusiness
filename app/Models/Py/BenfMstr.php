<?php

namespace App\Models\Py;

use Illuminate\Database\Eloquent\Model;

class BenfMstr extends Model {
  public $timestamps = false;
  protected $table = "py_benf_mstr";
  protected $primaryKey = "ID_benf";

  protected $fillable = [
    'description', 'type', 'fixed_amt', 'perctg', 'period', 'incl_gross',
    'active', 'category'
  ];

  public function details()
  {
    return $this->hasMany(BenfDetail::class, 'ID_benf', 'ID_benf');
  }
}
