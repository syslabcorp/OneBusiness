<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryChange extends Model
{
    protected $primaryKey = "Branch";
    protected $table = "s_changes";
    public $timestamps = false;

    protected $fillable = [
        'invtry_hdr', 'prodline', 'brands', 'items_cfg', 'services', 'rates', 'Branch', 'corp_id'
    ];
}
