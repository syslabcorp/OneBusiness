<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Company;
use Illuminate\Http\Request;
use App\Models\Corporation;
use App\Http\Controllers\Purchase\EquipTransformer;

class PurchasesController extends Controller
{
	public function index()
	{
		if(!\Auth::user()->checkAccessById(56, 'V')) {
				\Session::flash('error', "You don't have permission"); 
				return redirect("/home"); 
		}

		$company = Corporation::findOrFail(request()->corpID);
	
		return view('purchases.index', [
				'company' => $company
		]);
	}

	public function create()
	{
		// if(!\Auth::user()->checkAccessById(56, 'A')) {
		//     \Session::flash('error', "You don't have permission"); 
		//     return redirect("/home"); 
		// }
		
		$company = Corporation::findOrFail(request()->corpID);
		
		$branches = \App\Branch::all();
		$purchase = new \App\Models\Purchase\PurchaseRequest;
		$purchase->setConnection($company->database_name);

		return view('purchases.create', [
				'branches' => $branches,
				'purchase' => $purchase
		]);
	}

	public function store(Request $request)
	{
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseRequest;
		$purchaseModel->setConnection($company->database_name);
		
		$purchaseParams = request()->only([
				'requester_id', 'branch', 'description', 'date', 'total_qty', 'eqp_prt'
		]);

		$purchaseParams['date'] = date_create(request()->date) ? date_create(request()->date) : date('Y-m-d');
		
		$purchaseParams['flag'] = 2;
	
		$purchaseModel = $purchaseModel->create($purchaseParams);

		$updatePR = $purchaseModel->update([
				'pr' => $purchaseModel->id
		]);

		if ($purchaseParams['eqp_prt'] == 'equipment') {
			if (is_array(request()->parts)) {
				$purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
				$purchaseDetailModel->setConnection($company->database_name);
				foreach (request()->purchases as $purchase) {
					$detail_parents = $purchaseDetailModel->create([
						'purchase_request_id' => $purchaseModel->id,
						'item_id' => $purchase['item_id']
					]);
					
					foreach (request()->parts as $key => $part) {
						if ($key == $purchase['item_id']) {
							for ($i=1; $i <= count($part['item_id']) ; $i++) {
								$purchaseDetailModel->create([
										'purchase_request_id' => (int) $purchaseModel->id,
										'item_id' => (int) $part['item_id'][$i],
										'parent_id' => (int) $detail_parents->id,
										'qty_to_order' => (int) $part['qty'][$i]
									]);
							}
						}
					}
				}
			}
		} else if ($purchaseParams['eqp_prt'] == 'parts') {
			if (is_array(request()->purchases)) {
				$purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
				$purchaseDetailModel->setConnection($company->database_name);
				foreach (request()->purchases as $purchase) {
					$detail_parents = $purchaseDetailModel->create([
						'purchase_request_id' => $purchaseModel->id,
						'item_id' => $purchase['item_id'],
						'qty_to_order' => (int) $purchase['qty']
					]);
				}
			}
		}
		

		\Session::flash('success', 'New purchase request has been created');

		return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
	}

	public function show($id)
	{
		// if(!\Auth::user()->checkAccessById(56, 'V')) {
		//     \Session::flash('error', "You don't have permission"); 
		//     return redirect("/home"); 
		// }

		// $company = Corporation::findOrFail(request()->corpID);
		// $tab = 'auto';

		// $equipment = \App\Models\Equip\Hdr::findOrFail($id);

		// $deptModel = new \App\Models\T\Depts;
		// $deptModel->setConnection($company->database_name);

		// $deptItems = $deptModel->orderBy('department', 'ASC')
		//                         ->get();
		
		// $branches = \Auth::user()->getBranchesByArea(request()->corpID);
		// $vendors = \App\Models\Vendor::orderBy('VendorName', 'ASC')->get();
		// $brands = \App\Models\Equip\Brands::orderBy('description', 'ASC')->get();
		// $categories = \App\Models\Equip\Category::orderBy('description', 'ASC')->get();
		
		// $histories = $equipment->histories()
		//                     ->selectRaw('*, DATE_FORMAT(created_at, "%m/%d/%Y") as log_at')
		//                     ->orderBy('created_at', 'DESC')
		//                     ->get()
		//                     ->groupBy('log_at');
		
		// return view('purchases.edit');
	}

	public function edit(Request $request, $id) 
	{
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseRequest;
		$purchaseModel->setConnection($company->database_name);

		$purchase = $purchaseModel->find($id);
		
		$branches = \App\Branch::all();;

		// if (\Auth::user()->checkAccessById(58 , 'E')) {
		// 	// view MarkForPO use ACCESS_ID = 58
		// 	return view('purchases.MarkForPO',[
		// 		'purchase' => $purchase, 
		// 		'branches' => $branches, 
		// 		]);  
		// }

		if ($purchase->flag == 1) {
			//view For PO
			return view('purchases.detailPO', [
				'purchase' => $purchase, 
				'branches' => $branches, 
				]);
		} else if ($purchase->flag == 2) {
			//view edit
			return view('purchases.edit', [
				'purchase' => $purchase, 
				'branches' => $branches, 
				]);
		} else if ($purchase->flag == 3) {
				
		} else if ($purchase->flag == 4) {
				
		} else if ($purchase->flag == 5) {
			//view verify
			return view('purchases.verify',[
				'purchase' => $purchase, 
				'branches' => $branches, 
				]);
		} else if ($purchase->flag == 6) {
				
		} else if ($purchase->flag == 7) {
				
		}
		// if (\Auth::user()->checkAccessById(59 , 'E')) {
		//     if ($purchase->date_approved || $purchase->date_disapproved) {
		//             return view('purchases.date-approved', [
		//                     'purchase' => $purchase, 
		//                     'branches' => $branches, 
		//                     ]);
		//     } 
		// }

		// return view('purchases.PR-purchaser', [
		// 	'purchase' => $purchase, 
		// 	'branches' => $branches, 
		// 	]);
	}

	public function update(Request $request, $id)
	{
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseRequest;
		$purchaseModel->setConnection($company->database_name);
		
		$branches = \Auth::user()->getBranchesByArea(request()->corpID);

		$purchase_item = $purchaseModel->findOrFail($id);
		
		// if(\Auth::user()->checkAccessById(58, 'E')) {
		// 	$company = Corporation::findOrFail(request()->corpID);
		// 	$purchasedetailModel = new \App\Models\Purchase\PurchaseDetail;
		// 	$purchasedetailModel->setConnection($company->database_name);
			
		// 	if (request()->parts) {
		// 		if ($purchase_item->eqp_prt == 'equipment' ) {
		// 			foreach (request()->parts as $key => $part) {
		// 				foreach ($part as $key => $row) {
		// 					$purchase_item = $purchasedetailModel->findOrFail($key);
		// 					if ($row['qty_to_order'] != $purchase_item->qty_to_order) {
		// 						$purchase_item->purchaseRequest->update([
		// 							'flag' => 5
		// 						]);
								
		// 						$purchase_item->update([
		// 							'vendor_id' => $row['vendor_id'],
		// 							'remark' => $purchase_item->qty_to_order,
		// 							'qty_to_order' => $row['qty_to_order'],
		// 							'cost' => $row['cost'],
		// 							'isVerified' => 2
		// 						]);
		// 					} 
		// 				}
		// 			}
		// 		} else if ($purchase_item->eqp_prt == 'parts') {
		// 			foreach (request()->parts as $part) {
		// 				$purchase_item = $purchasedetailModel->findOrFail($part['part_id']);
		// 				if ($purchase_item->qty_to_order != $part['qty_to_order']) {
		// 					$purchase_item->purchaseRequest->update([
		// 						'flag' => 5
		// 					]);
		// 					$purchase_item->update([
		// 						'vendor_id' => $part['vendor_id'],
		// 						'remark' => $purchase_item->qty_to_order,
		// 						'qty_to_order' => $part['qty_to_order'],
		// 						'cost' => $part['cost'],
		// 						'isVerified' => 2
		// 					]);
		// 				} 
		// 			}
		// 		}
		// 	} 
	
		// 	if ($purchase_item->purchaseRequest->flag == 2) {	
		// 		$purchase_item->purchaseRequest->update(['flag' => 1]);
		// 	}
		// }
		
		
		// if ($request->approved) {
		//     $purchase->update([
		//         'date_approved' => date('Y-m-d')
		//     ]);
		// } else if($request->disapproved) {
		//     $purchase->update([
		//         'date_disapproved' => date('Y-m-d')
		//     ]);
				
		//     $purchase->details()->delete();

		//     return view('purchases.date-approved', [
		//         'purchase' => $purchase, 
		//         'branches' => $branches, 
		//         ]);
		// }
// ------------------------------------------------------------
		$purchase_item->update([
				'total_qty' => $request->total_qty
		]);
	
		$purchase_item->request_details()->delete();
			
		if ($purchase_item->eqp_prt == 'equipment') {
			if (is_array(request()->parts)) {
				$purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
				$purchaseDetailModel->setConnection($company->database_name);
				foreach (request()->purchases as $purchase) {
					$detail_parents = $purchaseDetailModel->create([
						'purchase_request_id' => $purchase_item->id,
						'item_id' => $purchase['item_id']
					]);
					
					foreach (request()->parts as $key => $part) {
						if ($key == $purchase['item_id']) {
							for ($i=1; $i <= count($part['item_id']) ; $i++) { 
								$purchaseDetailModel->create([
										'purchase_request_id' => (int) $purchase_item->id,
										'item_id' => (int) $part['item_id'][$i],
										'parent_id' => (int) $detail_parents->id,
										'qty_to_order' => (int) $part['qty'][$i]
									]);
							}
						}
					}
				}
			}	else {
				$purchase_item->request_details->each->delete();
			}
		} else if ($purchase_item->eqp_prt == 'parts') {
			if (is_array(request()->purchases)) {
				$purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
				$purchaseDetailModel->setConnection($company->database_name);
				foreach (request()->purchases as $purchase) {
					$detail_parents = $purchaseDetailModel->create([
						'purchase_request_id' => (int)$purchase_item->id,
						'item_id' => $purchase['item_id'],
						'qty_to_order' => (int) $purchase['qty']
					]);
				}
			} else {
				$purchase_item->request_details->each->delete();
			}
		}

		\Session::flash('success', 'Purchase # has been updated');
		
		return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
	}

	public function destroy($id){
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseRequest;
		$purchaseModel->setConnection($company->database_name);

		$purchase = $purchaseModel->findOrFail($id);
		
		$purchase->request_details()->delete();

		$purchase->delete();
	}

	public function getBrands()
	{  
		if (request()->radio == 'equipment') {
				$hdrModel = new \App\Models\Equip\Hdr;

				$items = $hdrModel->orderBy('asset_id')->get();

				return view('purchases.searchEQP', [
						'items' => $items
						]);
		} else if (request()->radio == 'parts') {
				$detailModel = new \App\Models\Equip\Detail;
				
				$itemparts = $detailModel->orderBy('item_id')->get();
				
				return view('purchases.searchPRT', [
						'itemparts' => $itemparts
						]);
		}
	}

	public function getParts() {
		$detailModel = new \App\Models\Equip\Detail;

		$items = $detailModel->where('asset_id', request()->equipmentID)->orderBy('item_id')->get();
		
		return view('purchases.showEQP', [
				'items' => $items
		]);
	}

	public function removePart() {
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseDetail;
		$purchaseModel->setConnection($company->database_name);

		$purchase_item = $purchaseModel->findOrFail(request()->partID);
	
		$purchase_item->update([
			'isVerified' => 1
		]);
		
		$purchase_item->purchaseRequest->update(['flag' => 5]);
	}
}
