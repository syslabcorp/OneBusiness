<?php

namespace App\Http\Models\Tables\t_master;

use Illuminate\Database\Eloquent\Model;

class wage_tmpl8_mstr extends Model
{
    protected $primaryKey = "wage_tmpl8_id";
    protected $connection = "mysql2";
    protected $table = 'wage_tmpl8_mstr';
    public $timestamps = false;
}
