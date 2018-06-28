<?php

namespace App\Http\Models\Tables\k_master;

use Illuminate\Database\Eloquent\Model;

class t_depts extends Model
{
    protected $primaryKey = "dept_ID";
    protected $connection = "k_master";
    protected $table = 't_depts';
    public $timestamps = false;
}
