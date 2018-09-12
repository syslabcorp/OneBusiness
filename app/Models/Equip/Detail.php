<?php

namespace App\Models\Equip;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model {
    public $timestamps = false;
    protected $table = "equip_detail";
    protected $primaryKey = "asset_id";

    protected $fillable = [
        'item_id', 'asset_id', 'qty', 'status'
    ];

    public function item()
    {
        return $this->belongsTo(\App\Models\Item\Master::class, 'item_id', 'item_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($detail) {
            $detail->item ? $detail->item()->delete() : null;
        });

    }
}
