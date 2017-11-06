<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    public $timestamps = false;
    protected $table = "t_sysdata";
    protected $primaryKey = "Branch";
    protected $connection = 'mysql';

    protected $fillable = [
        'Branch', 'Description', 'Street', 'City_ID', 'MaxUnits', 'Active', "ShortName",
        'StubHdr', 'StubMsg', 'MAC_Address', 'cashier_ip', 'RollOver', 'TxfrRollOver',
        'PosPtrPort', 'susp_ping_timeout', 'max_eload_amt', 'lc_uid', 'lc_pwd', 'to_mobile_num',
        'StubPrint', 'Modified', 'corp_id', 'MinimumChrg_Mins', 'CarryOverMins', 'RmTimeAlert',
        'RmOffAllowance', 'ChkInOveride', 'ChkOutOveride', 'CancelAllowance', 'TrnsfrAllowance'
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

    public function rates()
    {
        return $this->hasMany(\App\RateTemplate::class, "Branch", "Branch");
    }

    public function krates() {
        return $this->hasMany(\App\KRateTemplate::class, "Branch", "Branch");
    }

    public function rooms() {
      return $this->hasMany(\App\RoomStatus::class, "Branch", "Branch");
    }

    public function schedules() {
      return $this->hasMany(\App\RateSchedule::class, "Branch", "Branch");
    }

    public function company() {
        return $this->belongsTo(\App\Company::class, "corp_id", "corp_id");
    }
}
