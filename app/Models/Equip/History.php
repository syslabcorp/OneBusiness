<?php

namespace App\Models\Equip;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = "equip_histories";

    protected $fillable = [
        'content', 'changed_by', 'equipment_id', 'content', 'item_id'
    ];

    public function changedBy()
    {
        return $this->belongsTo(\App\User::class, 'changed_by', 'UserID');
    }

    public function item()
    {
        return $this->belongsTo(\App\Models\Item\Master::class, 'item_id', 'item_id');
    }

    public function equipment()
    {
        return $this->belongsTo(Hdr::class, 'equipment_id', 'asset_id');
    }
}
