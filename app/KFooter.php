<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KFooter extends Model
{
  public $timestamps = false;
  protected $table = "t_stubfoot";
  protected $primaryKey = "Foot_ID";
  protected $connection = 'k_master';

  protected $fillable = [
      'Foot_Text', 'sort', "Branch"
  ];
}
