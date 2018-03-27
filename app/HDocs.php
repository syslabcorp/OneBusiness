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
}
