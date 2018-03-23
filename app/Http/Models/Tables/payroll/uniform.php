<?php

namespace App\Http\Models\Tables\payroll;

use Illuminate\Database\Eloquent\Model;

class uniform extends Model
{
    protected $primaryKey = "Txn_ID";
    protected $connection = "nxpyrl";
    protected $table = 'uniforms';
    public $timestamps = false;
}
