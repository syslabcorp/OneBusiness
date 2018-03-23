<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Spotmpl8hdr extends Model
{
    public $timestamps = false;
    protected $table = "s_po_tmpl8_hdr";
    protected $primaryKey = "po_tmpl8_id";
    protected $connection = 'mysql2';

    protected $fillable = [
        'po_tmpl8_desc', 'city_id', 'po_avg_cycle',
        'active'];
}
