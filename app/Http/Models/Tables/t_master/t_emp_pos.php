<?php

namespace App\Http\Models\Tables\t_master;

use Illuminate\Database\Eloquent\Model;

class t_emp_pos extends Model
{
    protected $primaryKey = "pos_id";
    protected $connection = "mysql2";
    protected $table = 't_emp_pos';
    public $timestamps = false;
}
