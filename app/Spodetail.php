<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spodetail extends Model
{
    public $timestamps = false;
    protected $table = "s_po_detail";
    protected $primaryKey = "po_no";
    protected $connection = 'mysql2';

    protected $fillable = [
        'Branch', 'item_id', 'ItemCode',
        'Qty', 'ServedQty' ,'cost'
    ];

    public function item()
    {
        return $this->belongsTo(\App\StockItem::class, 'item_id', 'item_id');
    }
    
    public function brand() {
        return $this->belongsTo(\App\Brand::class, "Branch", "Brand_ID");
    }

}
