<?php

namespace App\Models\T;

use Illuminate\Database\Eloquent\Model;

class Depts extends Model
{
    public $timestamps = false;
    protected $table = "t_depts";
    protected $primaryKey = "dept_ID";

    protected $fillable = [
        'department', 'main'
    ];
}
