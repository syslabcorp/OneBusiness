<?php

namespace App\Http\Models\Tables\k_master;

use Illuminate\Database\Eloquent\Model;

class t_emp_pos extends Model
{
    protected $primaryKey = "pos_id";
    protected $connection = "k_master";
    protected $table = 't_emp_pos';
    public $timestamps = false;
    
}
