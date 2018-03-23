<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Corporation extends Model
{
    protected $table = 'corporation_masters';
    protected $primaryKey = 'corp_id';
    public $timestamps = 'false';

    public function branches()
    {
      return $this->hasMany(\App\Branch::class, "corp_id", "corp_id");
    }
}
