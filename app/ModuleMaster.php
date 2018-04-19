<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleMaster extends Model
{
    protected $primaryKey = "module_id";
    protected $connection = 'mysql';
}
