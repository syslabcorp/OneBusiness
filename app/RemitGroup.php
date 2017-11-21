<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RemitGroup extends Model
{
  public $timestamps = false;
  protected $table = "Remit_group";
  protected $primaryKey = "group_ID";

  protected $fillable = [
      
  ];
}
