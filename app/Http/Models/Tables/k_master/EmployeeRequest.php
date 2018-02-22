<?php

namespace App\Http\Models\Tables\k_master;

use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
	protected $connection = "k_master";
    	protected $table = 't_cashr_rqst';
}
