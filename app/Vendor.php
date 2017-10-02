<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 's_vendors';
    protected $primaryKey = 'Supp_ID';
    protected $fillable = [
        'VendorName', 'PayTo', 'Address', 'ContactPerson', 'TelNo', 'OfficeNo', 'CelNo',
        'x_check', 'petty_visible', 'withTracking'
    ];
    public $timestamps = false;

    public function vendorsManagement(){
        return $this->hasMany('App\VendorManagement', 'supp_id', 'Supp_ID');
    }
}
