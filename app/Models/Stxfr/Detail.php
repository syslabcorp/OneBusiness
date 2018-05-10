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
}
