<?php

namespace App\Models\Stxfr;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    public $timestamps = false;
    protected $table = "s_txfr_detail";
    protected $primaryKey = "Txfr_ID";

    protected $fillable = [
        'item_id', 'ItemCode', 'Qty',
        'Bal', 'Movement_ID', 'Txfr_ID'
    ];

    public function item()
    {
        return $this->belongsTo(\App\StockItem::class, 'item_id', 'item_id');
    }

    protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query->where('item_id', '=', $this->item_id)
            ->where('Movement_ID', '=', $this->Movement_ID)
            ->limit(1);

        return $query;
    }
}
