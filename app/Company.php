<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'corporation_masters';
    protected $primaryKey = 'corp_id';
    
    public function branchs() {
        return $this->hasMany(\App\Branch::class, 'corp_id', 'corp_id');
    }

    public function branches()
    {
        return $this->hasMany(\App\Branch::class, 'corp_id', 'corp_id');
    }
}
