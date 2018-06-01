<?php

namespace App\Models\T;

use Illuminate\Database\Eloquent\Model;

class Provinces extends Model
{
    public $timestamps = false;
    protected $table = "t_provinces";
    protected $primaryKey = "Prov_ID";
    protected $connection = 'mysql';

    protected $fillable = [
        'Province'
    ];
}
