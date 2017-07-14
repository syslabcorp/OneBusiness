<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = "t_sysdata";

    protected $fillable = [
        'Branch', 'Description', 'Street', 'City_ID', 'MaxUnits', 'Active',
        'StubHdr', 'StubMsg', 'MAC_Address', 'cashier_ip', 'RollOver', 'TxfrRollOver',
        'PostPtrPort', 'susp_ping_timeout', 'max_eload_amt', 'lc_uid', 'lc_pwd', 'to_mobile_num',
        'is_enable_printing'
    ];


    public function city()
    {
        return $this->belongsTo(\App\City::class, "City_ID", "City_ID");
    }

    // Relationships
    public function footers()
    {
        return $this->hasMany(\App\Footer::class, "Branch", "id");
    }

    public function macs()
    {
        return $this->hasMany(\App\Mac::class, "Branch", "id");
    }
}
