<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RightDetail extends Model
{
    protected $table = "rights_detail";

    public $timestamps = false;

    protected $fillable = [
        'module_id', 'template_id', 'feature_id', 'access_type'
    ];


}
