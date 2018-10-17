<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Item\Master;
use App\Transformers\Item\MasterTransformer;

class PartsController extends Controller
{
    public function index(Request $request){
        $items = Master::orderBy('item_id');

        if ($request->type == 'category') {
            $items = $items->where('cat_id', $request->id);
        }
        elseif($request->type == 'brand'){
            $items = $items->where('brand_id', $request->id);
        }
        elseif($request->type == 'vendor'){
            $items = $items->where('supplier_id', $request->id);
        }

        $items = $items->get();

        return fractal($items, new MasterTransformer)->toJson();
    }
}
