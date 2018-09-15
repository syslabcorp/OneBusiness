<?php

namespace App\Models\Equip;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = "equip_histories";

    protected $fillable = [
        'content', 'changed_by', 'equipment_id', 'content', 'item_id'
    ];
}
