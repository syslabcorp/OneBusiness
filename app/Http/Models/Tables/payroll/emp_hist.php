<?php

namespace App\Http\Models\Tables\payroll;

use Illuminate\Database\Eloquent\Model;

class emp_hist extends Model
{
    protected $primaryKey = "txn_id";
    protected $connection = "nxpyrl";
    protected $table = 'emp_hist';
    public $timestamps = false;
}
