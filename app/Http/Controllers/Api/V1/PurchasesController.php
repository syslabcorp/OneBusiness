<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Purchase\PurchasesTransformer;
use App\Models\Purchase\PurchaseRequest;

class PurchasesController extends Controller
{
    public function index(){
        $items = PurchaseRequest::all();
        
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
