<?php

namespace App\Http\Models\Tables\t_master;

use Illuminate\Database\Eloquent\Model;

class py_emp_hist extends Model
{
    protected $primaryKey = "txn_id";
    protected $connection = "mysql2";
    protected $table = 'py_emp_hist';
    public $timestamps = false;
}
