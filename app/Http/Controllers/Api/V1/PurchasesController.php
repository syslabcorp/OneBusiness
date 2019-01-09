<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Purchase\PurchasesTransformer;
use App\Models\Corporation;

class PurchasesController extends Controller
{
    public function index(Request $request){
        $company = Corporation::findOrFail($request->corpID);

        $purchaseModel = new \App\Models\Purchase\PurchaseRequest;
        $purchaseModel->setConnection($company->database_name);
        
        $items = $purchaseModel->get();
        
        if ($request->branch == 'requests') {
            $items = $items->where('flag', 2);
        }
        else if ($request->branch == 'all') {
            $items = $items->where('flag', 3);
        }
        else if ($request->branch == 'disapproved') {
            $items = $items->where('flag', 4);
        }
        else if ($request->branch == 'verify_request') {
            $items = $items->where('flag', 5);
        }
        else if ($request->branch == 'po_approved') {
            $items = $items->where('flag', 6);
        }
        else if ($request->branch == 'served') {
            $items = $items->where('flag', 7);
        }
        else {
            $items = $items->where('flag', 1);
        }
        
        return fractal($items, new PurchasesTransformer)->toJson();
    }
}
