<?php

namespace App\Http\Models\Tables\k_master;

use Illuminate\Database\Eloquent\Model;

class py_uniforms extends Model
{
    protected $primaryKey = "Txn_ID";
    protected $connection = "k_master";
    protected $table = 'py_uniforms';
    public $timestamps = false;
}
