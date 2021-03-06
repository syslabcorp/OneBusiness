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
		
		$branches = \Auth::User()->getBranchesByGroup(request()->corpID);
		
		if (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, 'V')) {
				$items = $purchaseModel->where('requester_id', \Auth::user()->UserID)->get();
		} else if (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'V')) {
				$items = $purchaseModel->whereIn('branch', $branches->pluck('Branch'))->get();
		}
		
		if ($request->branch == '1') {
				$items = $items->where('flag', 1);
		}
		else if ($request->branch == '2') {
				$items = $items->where('flag', 2);
		}
		else if ($request->branch == '4') {
				$items = $items->where('flag', 4);
		}
		else if ($request->branch == '5') {
				$items = $items->where('flag', 5);
		}
		else if ($request->branch == '6') {
				$items = $items->where('flag', 6);
		}
		else if ($request->branch == '7') {
				$items = $items->where('flag', 7);
		}
		
		return fractal($items, new PurchasesTransformer)->toJson();
    }

    public function destroy($id) {
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseRequest;
		$purchaseModel->setConnection($company->database_name);

		if (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, 'D') || \Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'D')) {
			$purchase = $purchaseModel->findOrFail($id);

			$purchase->request_details()->delete();

			$purchase->delete();
			
			\Session::flash('success', 'Purchase #' . $purchase->id . ' has been cancelled and deleted');
			
			return response()->json([
					'success' => true
			]);
		} 
    }
}