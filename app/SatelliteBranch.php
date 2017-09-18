<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatelliteBranch extends Model
{
    protected $table = 'pc_branches';
    protected $fillable = [
        'active', 'short_name', 'description', 'notes'
    ];
    protected $primaryKey = 'sat_branch';
    public $timestamps = false;
}
