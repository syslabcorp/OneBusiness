<?php

namespace App\Models\Branchs\Recommendation;

use Illuminate\Database\Eloquent\Model;

class Py_emp_rate extends Model
{
     //
    protected $primaryKey = 'approval_code';
    protected $connection = '';
    protected $table = 'py_emp_rate';
    public $timestamps = false;
    
    protected $fillable = [
        'txn_id',
        'wade_tmpl8_id',
        'effect_date',
        'date_changed'
        
    ];
}
