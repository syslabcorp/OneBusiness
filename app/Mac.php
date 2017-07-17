<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mac extends Model
{
    public $timestamps = false;
    protected $table = "t_rates";
    protected $primaryKey = "nKey";

    protected $fillable = [
        'PC_No', 'Mac_Address', 'IP_Addr', 'StnType', 'LastChgMAC',
        'LastChgMACDate', 'Branch'
    ];

    protected $dates = [
        'LastChgMACDate'
    ];

    // Relation Ships
    public function user()
    {
        return $this->belongsTo(\App\User::class, 'LastChgMAC', 'UserID');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Branch::class, "Branch", "Branch");
    }

    protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query
        ->where('Branch', '=', $this->Branch)
        ->where('nKey', '=', $this->nKey);
        return $query;
    }

}
