<?php

namespace App\Http\Models\Tables\t_master;

use Illuminate\Database\Eloquent\Model;

class py_deduct_mstr extends Model
{
    protected $primaryKey = "ID_deduct";
    protected $connection = "mysql2";
    protected $table = 'py_deduct_mstr';
    public $timestamps = false;
}
