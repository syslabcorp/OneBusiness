<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POTemplate extends Model
{
    public $timestamps = false;
    protected $table = "s_po_tmpl8_hdr";
    protected $primaryKey = "po_tmpl8_id";

    public function details() {
        return $this->hasMany(\App\POTemplateDetail::class, 'po_tmpl8_id', 'po_tmpl8_id');
    }
}
