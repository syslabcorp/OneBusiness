<?php

namespace App\Models\Equip;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model {
    public $timestamps = false;
    protected $table = "equip_detail";
    protected $primaryKey = "asset_id";

    const STATUSES = [
        '2' => 'For Repair',
        '1' => 'In Use',
        '0' => 'Retire'
    ];

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
    }

    protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query->where('item_id', '=', $this->item_id)
            ->where('asset_id', '=', $this->asset_id);

        return $query;
    }
}
