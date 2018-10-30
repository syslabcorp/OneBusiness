<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RightTemplate extends Model
{
    protected $table = "rights_template";

    protected $fillable = [
        'template_id', 'description', 'corp_id', 'template_menus', 'status', 'modified_at', 'is_super_admin'
    ];
}
