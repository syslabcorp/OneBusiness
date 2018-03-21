<?php

namespace App\Http\Models\Tables\og_payroll;

use Illuminate\Database\Eloquent\Model;

class emp_hist extends Model
{
    protected $primaryKey = "txn_id";
    protected $connection = "ogpyrl";
    protected $table = 'emp_hist';
    public $timestamps = false;
}
