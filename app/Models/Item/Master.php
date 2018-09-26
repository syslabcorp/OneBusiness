<?php

namespace App\Models\Item;

use Illuminate\Database\Eloquent\Model;

class Master extends Model {
    public $timestamps = false;
    protected $table = "item_master";
    protected $primaryKey = "item_id";

    protected $fillable = [
        'description', 'brand_id', 'cat_id', 'supplier_id', 'consumable', 'isActive'
    ];

    public function detail()
    {
        return $this->belongsTo(\App\Models\Equip\Detail::class, 'item_id', 'item_id');
    }

    public function histories()
    {
        return $this->hasMany(\App\Models\Equip\History::class, 'item_id', 'item_id');
    }

    protected static function boot()
    {
        parent::boot();

        self::created(function($item) {
            \App\Models\Equip\History::create([
                'changed_by' => \Auth::user()->UserID,
                'content' => 'details has been created',
                'item_id' => $item->item_id
            ]);
        });

        self::saving(function($item) {
            if ($item->isDirty()) {
                \App\Models\Equip\History::create([
                    'changed_by' => \Auth::user()->UserID,
                    'content' => 'details has been updated',
                    'item_id' => $item->item_id
                ]);
            }
        });
    }
}
