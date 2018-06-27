<?php

namespace App\Models\Branchs\Recommendation;

use Illuminate\Database\Eloquent\Model;

class Py_emp_hist extends Model
{
    //
    protected $primaryKey = 'txn_id';
    protected $connection = '';
    protected $table = 'py_emp_hist';
    
    protected $fillable = [
        'Branch',
        'EmpID',
        'StartDate',
        'EnDate',
        'Last13_Date',
        'for_qc',
        'print_date',
        'cut_off_id',
        'chk_num',
        'chk_date',
        'batcj_no'
    ];
}
