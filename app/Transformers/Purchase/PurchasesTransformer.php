<?php
namespace App\Transformers\Purchase;

use League\Fractal;
use App\Models\Purchase\PurchaseRequest;
use App\Models\Corporation;

class PurchasesTransformer extends Fractal\TransformerAbstract
{
    public function transform(PurchaseRequest $item)
    {
        return [
            'id' => (int) $item->id,
            'date' => $item->date,
            'job_order' => $item->job_order ? $item->job_order : '',
            'pr' => $item->pr,
            'description' => $item->description,
            'requester_id' => $item->user ? $item->user->UserName : '',
            'branch' => $item->getBranch ? $item->getBranch->ShortName : '',
            'total_qty' => $item->total_qty,
            'total_cost' => number_format($item->total_cost,2),
            'remarks' => $item->remarks,
            'date_disapproved' => $item->date_disapproved,
            'po' => $item->po,
            'disapproved_by' => $item->findUser() ? $item->findUser()->UserName : '',
            'pr_date' => $item->pr_date,
            'items_changed' => $item->items_changed,
            'vendor' => $item->vendor,
            'date_approved' => $item->date_approved,
            'approved_by' => $item->findUserApp() ? $item->findUserApp()->UserName : '',
            'eqp' => ($item->eqp_prt == 'Equipment') ? $item->eqp_prt : '',
            'prt' => ($item->eqp_prt == 'Part') ? $item->eqp_prt : '',
            'status' => $item->status,
            'flag' => $item->flag
        ];
    }
}