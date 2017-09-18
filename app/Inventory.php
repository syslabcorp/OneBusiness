<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = "s_invtry_hdr";
    protected $primaryKey = "item_id";
    public $timestamps = false;

    protected $fillable = [
      'itemCode', 'Brand_ID', 'Prod_Line', 'Descrition', 'Unit', 'Packaging', 'Threshold', 'Multiplier', 'Type',
        'Min_Level', 'Active', 'LastCost', 'barcode', 'TrackThis', 'Print_This'
    ];
}
