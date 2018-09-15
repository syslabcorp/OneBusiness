<?php

namespace App\Models\Equip;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    public $timestamps = false;
    protected $table = "equip_category";
    protected $primaryKey = "cat_id";

    protected $fillable = [
        'description'
    ];
}
