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
     
        // if ($request->type == 'category') {
        //     $items = $items->where('cat_id', $request->id);
        // }
        // elseif($request->type == 'brand'){
        //     $items = $items->where('brand_id', $request->id);
        // }
        // elseif($request->type == 'vendor'){
        //     $items = $items->where('supplier_id', $request->id);
        // }
        
        return fractal($items, new PurchasesTransformer)->toJson();
    }
}
