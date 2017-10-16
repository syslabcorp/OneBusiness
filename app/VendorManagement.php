<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorManagement extends Model
{
    protected $table = 'cv_vendacct';
    protected $primaryKey = 'acct_id';
    protected $fillable = [
      'supp_id', 'acct_num', 'nx_branch', 'description', 'days_offset',
      'firstday_offset', 'active'
    ];
    public $timestamps = false;

    public function vendors(){
        return $this->belongsTo('App\Vendor', 'supp_id');
    }

    public function corporations(){
        return $this->belongsTo('App\Corporation', 'corp_id');
    }
}
