<?php

namespace App\Models\Srcv;

use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    public $timestamps = false;
    protected $table = "s_rcv_detail";
    protected $primaryKey = "RR_No";

    protected $fillable = [
        'RcvDate', 'item_id', 'ItemCode',
        'ServedQty', 'Qty' ,'Bal','Cost','Movement_ID','RMA_Qty'];

    protected function setKeysForSaveQuery(\Illuminate\Database\Eloquent\Builder $query)
    {
        $query->where('item_id', '=', $this->item_id)
                ->where('Movement_ID', '=', $this->Movement_ID);
    
        return $query;
    }
}
