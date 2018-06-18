<?php

namespace App\Http\Models\Tables\t_master;

use Illuminate\Database\Eloquent\Model;

class h_docs extends Model
{
    protected $primaryKey = "txn_no";
    protected $connection = "mysql2";
    protected $table = 'h_docs';
    public $timestamps = false;
}
