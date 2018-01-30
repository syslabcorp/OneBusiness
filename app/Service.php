<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = "services";
    protected $primaryKey = "Serv_ID";
    public $timestamps = false;

    protected $fillable = [
      'Serv_Code', 'Description', 'Active'
    ];
}
