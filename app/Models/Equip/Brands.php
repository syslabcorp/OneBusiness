<?php

namespace App\Models\Equip;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model {
    public $timestamps = false;
    protected $table = "equip_brands";
    protected $primaryKey = "brand_id";

    protected $fillable = [
        'description'
    ];
}
