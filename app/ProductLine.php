<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductLine extends Model
{
    protected $table = "s_prodline";
    protected $primaryKey = "ProdLine_ID";
    public $timestamps = false;

    protected $fillable = [
        'Product', 'Active'
    ];
}
