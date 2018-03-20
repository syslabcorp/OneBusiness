<?php

namespace App\Http\Models\Tables\og_payroll;

use Illuminate\Database\Eloquent\Model;

class uniform extends Model
{
    protected $primaryKey = "Txn_ID";
    protected $connection = "ogpyrl";
    protected $table = 'uniforms';
    public $timestamps = false;
}
