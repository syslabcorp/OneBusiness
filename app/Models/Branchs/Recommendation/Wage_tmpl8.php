<?php

namespace App\Models\Branchs\Recommendation;

use Illuminate\Database\Eloquent\Model;

class Wage_tmpl8 extends Model
{
    //
    protected $primaryKey = "wage_tmpl8_id";
    protected $connection = '';
    protected $table = 'wage_tmpl8_mstr';
    
    protected $fillable = [
        'code',
        'position',
        'base_rate',
        'entry_level',
        'notes',
        'contract',
        'dept_id',
        'active'
    ];
}
