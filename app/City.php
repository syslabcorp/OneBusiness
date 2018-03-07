<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;
    protected $table = "t_cities";
    protected $primaryKey = 'City_ID';

    public function province()
    {
        return $this->belongsTo(\App\Province::class, "Prov_ID", "Prov_ID");
    }

    public function branchs()
    {
        return $this->hasMany(\App\Branch::class, "City_ID", "City_ID");
    }
}
