<?php

namespace App\Models\T;

use Illuminate\Database\Eloquent\Model;

class Dtr extends Model
{
    public $timestamps = false;
    protected $table = "t_dtr";
    protected $primaryKey = "Txn";

    protected $fillable = [
    ];

    public function branch()
    {
        return $this->belongsTo(\App\Branch::class, 'Branch' , 'Branch');
    }
}
