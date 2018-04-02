<?php

namespace App\Pc;

use Illuminate\Database\Eloquent\Model;

class Subcat extends Model {
  public $timestamps = false;
  protected $table = "pc_subcat";
  protected $primaryKey = "subcat_id";

  protected $fillable = [
    'description', 'deleted', 'active'
  ];
}
