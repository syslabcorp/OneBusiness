<?php

namespace App\Http\Models\Tables\k_master;

use Illuminate\Database\Eloquent\Model;

class h_category extends Model
{
    protected $primaryKey = "doc_no";
    protected $connection = "k_master";
    protected $table = 'h_category';
    public $timestamps = false;
}
