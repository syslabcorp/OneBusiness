<?php

namespace App\Http\Models\Tables\t_master;

use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    protected $connection = "t_master";
    protected $table = 't_cashr_rqst';
}
