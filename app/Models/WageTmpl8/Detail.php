<?php

namespace App\Models\WageTmpl8;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model {
    public $timestamps = false;
    protected $table = "wage_tmpl8_detail";
    protected $primaryKey = "wage_tmpl8_id";

    protected $fillable = [
        'ID', 'pay_db'
    ];
}
