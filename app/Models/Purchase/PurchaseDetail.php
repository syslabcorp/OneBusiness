<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_request_details';
    
    protected $fillable = [
        'purchase_request_id', 'eqp', 'prt', 'item_id', 'qty_to_order', 'created_at', 'updated_at'
    ];

}
