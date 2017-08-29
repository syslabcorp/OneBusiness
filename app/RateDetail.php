<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RateDetail extends Model
{
    public $timestamps = false;
    protected $table = "t_rates_detail";
    protected $primaryKey = "nKey";

    protected $fillable = [
        'template_id', 'MinAmt1', 'MinAmt1', 'MinAmt2', 'MinAmt3', 'Net_1', "Net_2",
        'Net_3', 'Z1min_5', 'Z1min_10', 'Z1min_15', 'Z1min_20', 'Z1min_25',
        'Z1min_30', 'Z1min_35', 'Z1min_40', 'Z1min_45', 'Z1min_50', 'Z1min_55',
        'Z1min_60', 'Z2min_5', 'Z2min_10', 'Z2min_15', 'Z2min_20', 'Z2min_25',
        'Z2min_30', 'Z2min_35', 'Z2min_40', 'Z2min_45', 'Z2min_50', 'Z2min_55',
        'Z2min_60', 'Z3min_5', 'Z3min_10', 'Z3min_15', 'Z3min_20', 'Z3min_25',
        'Z3min_30', 'Z3min_35', 'Z3min_40', 'Z3min_45', 'Z3min_50', 'Z3min_55',
        'Z3min_60', 'nKey'
    ];
}
