<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Item\Master;
use App\Transformers\Item\MasterTransformer;

class PartsController extends Controller
{
    public function index(Request $request){
        $items = Master::all();

        return fractal($items, new MasterTransformer)->toJson();
    }
}
