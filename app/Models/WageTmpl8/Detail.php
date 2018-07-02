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

    public function benefit()
    {
        return $this->belongsTo(\App\Models\Py\BenfMstr::class, 'ID_benf', 'ID');
    }

    public function deduct()
    {
      return $this->belongsTo(\App\Models\Py\DeductMstr::class, 'ID_deduct', 'ID');
    }

    public function exp()
    {
      return $this->belongsTo(\App\Models\Py\ExpMstr::class, 'ID_exp', 'ID');
    }

    public function benf_mstr()
    {
      return $this->where('pay_db', 'benf_mstr');
    }

    public function exp_mstr()
    {
      return $this->where('pay_db', 'exp_mstr');
    }

    public function deduct_mstr()
    {
      return $this->where('pay_db', 'deduct_mstr');
    }
}
