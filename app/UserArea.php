<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserArea extends Model
{
    public $timestamps = false;
    protected $table = "user_area";
    protected $primaryKey = "user_ID";
    protected $connection = 'mysql';
}
