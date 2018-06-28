<?php

namespace App\Http\Models\Tables\k_master;

use Illuminate\Database\Eloquent\Model;

class py_deduct_mstr extends Model
{
    protected $primaryKey = "ID_deduct";
    protected $connection = "k_master";
    protected $table = 'py_deduct_mstr';
    public $timestamps = false;
}
