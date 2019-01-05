<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    protected $connection = "t_master";
    protected $table = 'purchase_requests';
    
    protected $fillable = [
        'id', 'date', 'job_order', 'pr', 'description', 'requester', 'branch', 
        'total_qty', 'total_cost', 'status', 'remarks', 'date_disapproved', 'po',
        'disapproved_by', 'pr_date', 'items_changed', 'vendor', 'created_at', 'updated_at'
    ];

}
