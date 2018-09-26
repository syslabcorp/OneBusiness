<?php

namespace App\Models\Equip;

use Illuminate\Database\Eloquent\Model;

class Hdr extends Model {
    public $timestamps = false;
    protected $table = "equip_hdr";
    protected $primaryKey = "asset_id";

    protected $fillable = [
        'description', 'branch', 'dept_id', 'type', 'jo_dept', 'isActive'
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

    protected static function boot()
    {
        parent::boot();

        self::created(function($item) {
            \App\Models\Equip\History::create([
                'changed_by' => \Auth::user()->UserID,
                'content' => 'has been created',
                'equipment_id' => $item->asset_id
            ]);
        });

        self::saving(function($item) {
            if ($item->isDirty()) {
                \App\Models\Equip\History::create([
                    'changed_by' => \Auth::user()->UserID,
                    'content' => 'has been updated',
                    'equipment_id' => $item->asset_id
                ]);
            }
        });

        static::deleting(function($equipment) {
            $equipment->details->each->delete();
            $equipment->histories->each->delete();
        });
    }
}
