<?php

namespace App\Http\Models\Tables\t_master;

use Illuminate\Database\Eloquent\Model;

class py_uniforms extends Model
{
    protected $primaryKey = "Txn_ID";
    protected $connection = "mysql2";
    protected $table = 'py_uniforms';
    public $timestamps = false;
}
