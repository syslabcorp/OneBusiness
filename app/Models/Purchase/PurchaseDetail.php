<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_request_details';
    
    protected $fillable = [
        'eqp', 'prt', 'item_name', 'qty_to_order', 'created_at', 'updated_at'
    ];

}
