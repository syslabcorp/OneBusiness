<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = "s_brands";
    protected $primaryKey = "Brand_ID";
    public $timestamps = false;

    protected $fillable = [
        'Brand'
    ];
}
