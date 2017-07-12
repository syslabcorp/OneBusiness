<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $table = "t_sysdata";

    protected $fillable = [
        'branch_name', 'description', 'street', 'city_id', 'max_units', 'active',
        'stub_hdr', 'stub_msg', 'mac_address', 'cashier_ip', 'roll_over', 'txfr_roll_over',
        'pos_ptr_port', 'susp_ping_timeout', 'max_eload_amt', 'lc_uid', 'lc_pwd', 'to_mobile_num',
        'is_enable_printing'
    ];


    public function city()
    {
        return $this->belongsTo(\App\City::class);
    }

    // Relationships
    public function footers()
    {
        return $this->hasMany(\App\Footer::class);
    }

    public function macs()
    {
        return $this->hasMany(\App\Mac::class);
    }
}
