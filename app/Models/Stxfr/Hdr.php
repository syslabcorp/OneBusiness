<?php

namespace App\Models\Stxfr;

use Illuminate\Database\Eloquent\Model;

class Hdr extends Model
{
    public $timestamps = false;
    protected $table = "s_txfr_hdr";
    protected $primaryKey = "Txfr_ID";

    protected $fillable = [
        'Txfr_Date', 'Txfr_To_Branch', 'Rcvd',
        'DateRcvd', 'Shift_ID', 'Uploaded'
    ];


    public function branch()
    {
        return $this->belongsTo(\App\Branch::class, 'Txfr_To_Branch', 'Branch');
    }

    public function details()
    {
        return $this->hasMany(Detail::class, 'Txfr_ID', 'Txfr_ID');
    }

    public function shift()
    {
        return $this->belongsTo(\App\Models\T\Shift::class, 'Shift_ID', 'Shift_ID');
    }
}
