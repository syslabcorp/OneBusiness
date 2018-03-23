<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkbook extends Model
{
    protected $table = 'cv_chkbk_series';
    protected $fillable = [
        'bank_acct_id', 'order_num', 'chknum_start', 'chknum_end', 'lastchknum', 'used', 'bank_code'
    ];
    protected $primaryKey = 'txn_no';
    public $timestamps = false;
}
