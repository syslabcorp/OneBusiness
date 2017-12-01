<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POTemplate5 extends Model
{
    public $timestamps = false;
    protected $table = "s_po_tmpl8_hdr";
    protected $primaryKey = "po_tmpl8_id";
    protected $connection = 'l_master';
}
