<?php

namespace App\Http\Models\Tables\t_master;

use Illuminate\Database\Eloquent\Model;

class t_depts extends Model
{
    protected $primaryKey = "dept_ID";
    protected $connection = "mysql2";
    protected $table = 't_depts';
    public $timestamps = false;
}
