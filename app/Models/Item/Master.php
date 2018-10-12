<?php

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class Master extends Model {
    public $timestamps = false;
    protected $table = "item_master";
    protected $primaryKey = "item_id";

    protected $fillable = [
        'item_id', 'brand_id', 'cat_id', 'supplier_id', 'description', 'consumable',  
        'with_serialno', 'jo_dept', 'isActive'
    ];

    public function detail()
    {
        return $this->belongsTo(\App\Models\Equip\Detail::class, 'item_id', 'item_id');
    }

    public function brand()
    {
        return $this->belongsTo(\App\Models\Equip\Brands::class, 'brand_id', 'brand_id');
    }

    public function category(){
        return $this->belongsTo(\App\Models\Equip\Category::class, 'cat_id', 'cat_id');
    }

    public function vendor(){
        return $this->belongsTo(\App\Models\Vendor::class, 'supplier_id', 'Supp_ID');
    }

    protected static function boot()
    {
        parent::boot();
    }
}