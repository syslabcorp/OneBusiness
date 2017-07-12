<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mac extends Model
{
    protected $table = "t_rates";

    protected $fillable = [
        'PC_No', 'Mac_Address', 'IP_Addr', 'StnType', 'LastChgMAC',
        'LastChgMACDate', 'Branch'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'LastChgMACDate'
    ];

    // Relation Ships
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'LastChgMAC', 'UserID');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Branch::class);
    }

}
