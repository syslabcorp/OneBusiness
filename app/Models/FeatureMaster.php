<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureMaster extends Model
{
    protected $table = "feature_masters";

    public $timestamps = false;

    protected $fillable = [
        'feature_id', 'feature', 'module_id', 'status', 'deleted', 'created_at', 'modified_at'
    ];
}
