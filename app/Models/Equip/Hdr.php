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
        return $this->belongsTo(\App\Branch::class, 'branch', 'Branch');
    }
}
