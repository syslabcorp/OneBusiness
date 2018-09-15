<?php

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class Master extends Model {
    public $timestamps = false;
    protected $table = "item_master";
    protected $primaryKey = "item_id";

    protected $fillable = [
        'description', 'brand_id', 'cat_id', 'supplier_id', 'consumable'
    ];

    public function detail()
    {
        return $this->belongsTo(\App\Models\)
    }

    protected static function boot()
    {
        parent::boot();

        self::created(function($item) {

        });
    }
}
