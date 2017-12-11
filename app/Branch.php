<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
			'StubPrint', 'Modified', 'corp_id', 'MinimumChrg_Mins', 'CarryOverMins', 'RmTimerAlert',
			'RmOffAllowance', 'ChkInOveride', 'ChkOutOveride', 'CancelAllowance', 'TrnsfrAllowance',
			'Chrg_Min'
    ];

    public function corp()
    {
      return $this->belongsTo(\App\Corporation::class, "corp_id", "corp_id");
    }

    public function city()
    {
      return $this->belongsTo(\App\City::class, "City_ID", "City_ID");
    }

    public function remittanceDetails()
    {
      $instance = new \App\RemittanceDetail;
      $instance->setConnection($this->corp->database_name);

      return new HasMany($instance->newQuery(), $this, "Branch", "Branch");
    }

    public function remittances()
    {
      return $this->hasMany(\App\TRemittance::class, "Branch", "Branch");
    }
    // Relationships

    public function getTotalAllRemittanceCollections(){
        $total = 0;
        if($this->remittance_collections)
        {
            $remittance_collections = $this->remittanceDetails()->get();
            foreach($remittance_collections as $remittance_collection )
            {
                $total += $remittance_collection->Collection;
            }
        }
        return $total;
    }

    public function footers()
    {
			if($this->company && $this->company->corp_type == 'INN') {
				return $this->hasMany(\App\KFooter::class, "Branch", "Branch");
			}else {
				return $this->hasMany(\App\Footer::class, "Branch", "Branch");
			}
        
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
