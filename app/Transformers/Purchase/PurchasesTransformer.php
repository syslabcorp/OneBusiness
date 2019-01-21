<?php
namespace App\Transformers\Purchase;

use League\Fractal;
use App\Models\Purchase\PurchaseRequest;
use App\Models\Corporation;

class PurchasesTransformer extends Fractal\TransformerAbstract
{
    public function transform(PurchaseRequest $item)
    {
        $company = Corporation::findOrFail(request()->corpID);
      
        $detailModel = new \App\Models\Purchase\PurchaseDetail;
        $detailModel->setConnection($company->database_name);
        
        $detail = $detailModel->find($item->id);

        return [
            'id' => (int) $item->id,
            'date' => $item->date,
            'job_order' => $item->job_order,
            'pr' => $item->pr,
            'description' => $item->description,
            'requester_id' => $item->requester_id,
            'branch' => $item->branch,
            'total_qty' => $item->total_qty,
            'total_cost' => $item->total_cost,
            'status' => $item->status,
            'remarks' => $item->remarks,
            'date_disapproved' => $item->date_disapproved,
            'po' => $item->po,
            'disapproved_by' => $item->disapproved_by,
            'pr_date' => $item->pr_date,
            'items_changed' => $item->items_changed,
            'vendor' => $item->vendor,
            'date_approved' => $item->date_approved,
            'approved_by' => $item->approved_by,
            'eqp' => $detail ? $detail->eqp : 'NULL',
            'prt' => $detail ? $detail->prt : 'NULL'
        ];
    }
}