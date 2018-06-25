<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tmaster extends Model
{
    public $timestamps = false;
    protected $table = "s_po_hdr";
    protected $primaryKey = "po_no";
    protected $connection = 'mysql2';

    protected $fillable = [
        'po_date', 'tot_pcs', 'Prodserved_Line',
        'total_amt', 'po_tmpl8_id' ];


    public function getTmasterDetailData()
    {
        return $this->hasMany('App\Spodetail','po_no');
    }

    public function template() {
        return $this->belongsTo(Models\Spo\Tmpl8Hdr::class);
    }


}
