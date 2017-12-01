<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POTemplateDetail3 extends Model
{
    public $timestamps = false;
    protected $table = "s_po_tmpl8_detail";
    protected $primaryKey = "po_tmpl8_id";
    protected $connection = 'k_master';
}
