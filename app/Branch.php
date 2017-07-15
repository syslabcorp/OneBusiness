<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public $timestamps = false;
    protected $table = "t_sysdata";
    protected $primaryKey = "Branch";

    protected $fillable = [
        'Branch', 'Description', 'Street', 'City_ID', 'MaxUnits', 'Active', "ShortName",
        'StubHdr', 'StubMsg', 'MAC_Address', 'cashier_ip', 'RollOver', 'TxfrRollOver',
        'PosPtrPort', 'susp_ping_timeout', 'max_eload_amt', 'lc_uid', 'lc_pwd', 'to_mobile_num',
        'StubPrint'
    ];


    public function city()
    {
        return $this->belongsTo(\App\City::class, "City_ID", "City_ID");
    }

    // Relationships
    public function footers()
    {
        return $this->hasMany(\App\Footer::class, "Branch", "Branch");
    }

    public function macs()
    {
        return $this->hasMany(\App\Mac::class, "Branch", "Branch");
    }
}
