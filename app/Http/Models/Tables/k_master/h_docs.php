<?php

namespace App\Http\Models\Tables\k_master;

use Illuminate\Database\Eloquent\Model;

class h_docs extends Model
{
    protected $primaryKey = "txn_no";
    protected $connection = "k_master";
    protected $table = 'h_docs';
    public $timestamps = false;
}
