<?php

namespace App\Models\Equip;

use Illuminate\Database\Eloquent\Model;

class Hdr extends Model {
    public $timestamps = false;
    protected $table = "equip_hdr";
    protected $primaryKey = "asset_id";

    protected $fillable = [
    ];

    public function Branch()
    {
        return $this->belongsTo(\App\Branch::class, 'branch', 'Branch');
    }
}
