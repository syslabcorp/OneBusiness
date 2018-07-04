<?php

namespace App\Http\Models\Tables\t_master;

use Illuminate\Database\Eloquent\Model;

class h_category extends Model
{
    protected $primaryKey = "doc_no";
    protected $connection = "mysql2";
    protected $table = 'h_category';
    public $timestamps = false;
}