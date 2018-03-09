<?php

namespace App\Http\Models\Tables\k_master;

use Illuminate\Database\Eloquent\Model;

class t_emp_rate extends Model
{
    protected $primaryKey = "rate_id";
    protected $connection = "k_master";
    protected $table = 't_emp_rate';
    public $timestamps = false;
}
