<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_request_details';
    
    protected $fillable = [
        'purchase_request_id', 'item_id', 'equipment_id', 'isVerified', 'qty_to_order', 'qty_old', 'remark', 'vendor_id', 'date_verified', 'cost', 'created_at', 'updated_at'
    ];

    public function parts() 
    {
        return $this->hasMany(PurchaseDetail::class,'equipment_id','id');
    }

    public function equipment() 
    {
        return \App\Models\Equip\Hdr::find($this->item_id);
    }

    public function itemMaster()
    {
        return \App\Models\Item\Master::find($this->item_id);
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class,'purchase_request_id');
    }

    public function vendor()
    {
        return $this->belongsTo(\App\Vendor::class, 'vendor_id');
    }
}