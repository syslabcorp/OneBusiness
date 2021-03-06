<?php

namespace App\Models\WageTmpl8;

use Illuminate\Database\Eloquent\Model;

class Mstr extends Model {
    public $timestamps = false;
    protected $table = "wage_tmpl8_mstr";
    protected $primaryKey = "wage_tmpl8_id";

    protected $fillable = [
        'code', 'position', 'base_rate', 'entry_level', 'notes', 'dept_id',
        'active', 'contract'
    ];

    public function department()
    {
        return $this->belongsTo(\App\Models\T\Depts::class, 'dept_id', 'dept_ID');
    }

    public function details()
    {
        return $this->hasMany(Detail::class, 'wage_tmpl8_id', 'wage_tmpl8_id');
    }

    public function totalAmt()
    {
        return $this->details()
                    ->leftJoin((new \App\Models\Py\BenfMstr)->getTable(), 'ID', 'ID_benf')
                    ->where('pay_db', 'benf_mstr')
                    ->where('type', 3)
                    ->sum('fixed_amt');
    }
}
