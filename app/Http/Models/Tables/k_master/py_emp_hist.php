<?php

namespace App\Http\Models\Tables\k_master;

use Illuminate\Database\Eloquent\Model;

class py_emp_hist extends Model
{
    protected $primaryKey = "txn_id";
    protected $connection = "k_master";
    protected $table = 'py_emp_hist';
    public $timestamps = false;
}
