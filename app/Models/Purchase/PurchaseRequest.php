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
        'flag', 'eqp_prt', 'is_editing_by', 'is_editing_at', 'created_at', 'updated_at'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'is_editing_at'
    ];

    public function request_details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_request_id','id');
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class)->whereNull('equipment_id');
    }

    public function user() 
    {
        return $this->belongsTo(\App\User::class, 'requester_id', 'UserID');
    }

    public function findUser() 
    {
        return \App\User::find($this->disapproved_by);
    }

    public function findUserApp() 
    {
        return \App\User::find($this->approved_by);
    }


    public function getBranch() 
    {
        return $this->belongsTo(\App\Models\Branch::class, 'branch');
    }
}
