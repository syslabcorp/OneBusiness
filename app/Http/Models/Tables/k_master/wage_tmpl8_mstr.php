<?php

namespace App\Http\Models\Tables\k_master;

use Illuminate\Database\Eloquent\Model;

class wage_tmpl8_mstr extends Model
{
    protected $primaryKey = "wage_tmpl8_id";
    protected $connection = "k_master";
    protected $table = 'wage_tmpl8_mstr';
    public $timestamps = false;
}
