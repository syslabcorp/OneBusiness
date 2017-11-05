<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KRateDetail extends Model
{
    public $timestamps = false;
    protected $table = "t_rates_detail";
    protected $primaryKey = "nKey";
    protected $connection = 'k_master';

    protected $fillable = [
      'tmplate_id', 'MinAmt1', 'MinAmt2', 'MinAmt3', 'Hr_1', "Hr_2",
      'Hr_3', 'Hr_4', 'Hr_5', 'Hr_6', 'Hr_7', 'Hr_8',
      'Hr_9', 'Hr_10', 'Hr_11', 'Hr_12', 'Hr_13', 'Hr_14',
      'Hr_15', 'Hr_16', 'Hr_17', 'Hr_18', 'Hr_19', 'Hr_20',
      'Hr_21', 'Hr_22', 'Hr_23', 'Hr_24', 'nKey'
    ];
}
