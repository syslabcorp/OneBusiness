<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\Stock\StockTransformer;
use App\Corporation;

class StocksController extends Controller
{
    public function index(Request $request){
        $company = Corporation::findOrFail($request->corpID);
        $stockModel = new \App\Stock;
        $stockModel->setConnection($company->database_name);

        if (request()->vendor_id) {
            $stockModel = $stockModel->where('Supp_ID', request()->vendor_id);
        }

        $stocks = $stockModel->get();
  
        return fractal($stocks, new StockTransformer)->toJson();
    }

    public function destroy(Request $request, $txnNo)
    {
        $company = Corporation::findOrFail($request->corpID);
        $stockModel = new \App\Stock;
        $stockModel->setConnection($company->database_name);

        $stock = $stockModel->findOrFail($txnNo);
        $stock->delete();

        return response()->json([
            'success' => true
        ]);
    }
    
}
