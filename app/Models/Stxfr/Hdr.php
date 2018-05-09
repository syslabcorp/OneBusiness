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
}
