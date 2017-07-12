<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mac extends Model
{
    protected $table = "t_rates";

    protected $fillable = [
        'pc_no', 'mac_address', 'ip_address', 'stn_type', 'last_changed_by',
        'last_changed_at', 'branch_id'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'last_changed_at'
    ];

    // Relation Ships
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'last_changed_by', 'UserID');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Branch::class);
    }

}
