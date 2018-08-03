<?php

namespace App\Models\Py;

use Illuminate\Database\Eloquent\Model;

class EmpRate extends Model
{
    public $timestamps = false;
    protected $table = "py_emp_rate";

    protected $fillable = [
        'txn_id', 'wage_tmpl8_id', 'effect_date', 'date_changed'
    ];

    public function empHistory()
    {
      return $this->belongsTo(EmpHistory::class, 'txn_id', 'txn_id');
    }

    public function mstr()
    {
      return $this->belongsTo(\App\Models\WageTmpl8\Mstr::class, 'wage_tmpl8_id', 'wage_tmpl8_id');
    }
}
