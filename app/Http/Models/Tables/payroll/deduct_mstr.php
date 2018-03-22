<?php

namespace App\Http\Models\Tables\payroll;

use Illuminate\Database\Eloquent\Model;

class deduct_mstr extends Model
{
    protected $primaryKey = "ID_deduct";
    protected $connection = "nxpyrl";
    protected $table = 'deduct_mstr';
    public $timestamps = false;
}