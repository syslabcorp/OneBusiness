<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    protected $table = 'purchase_requests';

    protected $fillable = [
        'date', 'job_order', 'pr', 'description', 'requester_id', 'branch', 
        'total_qty', 'total_cost', 'status', 'remarks', 'date_disapproved', 'po',
        'disapproved_by', 'pr_date', 'items_changed', 'vendor', 'date_approved', 'approved_by', 
        'flag', 'created_at', 'updated_at'
    ];

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}
