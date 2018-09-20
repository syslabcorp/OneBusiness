<?php

namespace App\Models\Equip;

use Illuminate\Database\Eloquent\Model;

class Hdr extends Model {
    public $timestamps = false;
    protected $table = "equip_hdr";
    protected $primaryKey = "asset_id";

    protected $fillable = [
        'description', 'branch', 'dept_id', 'type', 'jo_dept'
    ];

    public function branchObj()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch', 'Branch');
    }

    public function details()
    {
        return $this->hasMany(Detail::class, 'asset_id', 'asset_id');
    }

    public function histories()
    {
        return $this->hasMany(History::class, 'equipment_id', 'asset_id');
    }
}
