<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = "t_sysdata";

    protected $fillable = [
        'branch_name', 'description', 'street', 'city_id', 'max_units', 'active'
    ];


    public function city()
    {
        return $this->belongsTo(\App\City::class);
    }
}
