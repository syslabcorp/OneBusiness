<?php

namespace App\Models\Branchs\Recommendation;

use Illuminate\Database\Eloquent\Model;

class H_docs extends Model
{
    //
    protected $primaryKey = 'txn_no';
    protected $connection = '';
    protected $table = 'h_docs';
    public $timestamps = false;
    
    protected $fillable = [
        'series_no',
        'doc_no',
        'subcat_id',
        'emp_id',
        'branch',
        'notes',
        'doc_date',
        'doc_exp',
        'img_file'
    ];
}
