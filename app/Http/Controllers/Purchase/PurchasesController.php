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
		if (\Auth::user()->checkAccessById(58, 'V') || \Auth::user()->checkAccessById(59, 'V')) {
			$company = Corporation::findOrFail(request()->corpID);
	
			return view('purchases.index', [
					'company' => $company
			]);
		} else {
			\Session::flash('error', "You don't have permission"); 
			return redirect("/home");
		}
	}

	public function create()
	{
		if (\Auth::user()->checkAccessById(58, 'A') || \Auth::user()->checkAccessById(59, 'A')) {
			$company = Corporation::findOrFail(request()->corpID);
		
			$branches = \Auth::user()->getBranchesByArea(request()->corpID);

			$purchase = new \App\Models\Purchase\PurchaseRequest;
			$purchase->setConnection($company->database_name);
			
			return view('purchases.create', [
					'branches' => $branches,
					'purchase' => $purchase
			]);
		}
		else {
			\Session::flash('error', "You don't have permission"); 
		    return redirect("/home"); 
		}
	}

	public function store(Request $request)
	{
		if(!\Auth::user()->checkAccessById(58, 'A')) {
			\Session::flash('error', "You don't have permission"); 
			return redirect("/home"); 
		}
		
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
			if (is_array(request()->purchases)) {
				$purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
				$purchaseDetailModel->setConnection($company->database_name);
				foreach (request()->purchases as $purchase) {
					$detail_parents = $purchaseDetailModel->create([
						'purchase_request_id' => $purchaseModel->id,
						'item_id' => $purchase['item_id']
					]);
					if (request()->parts) {
						foreach (request()->parts as $key => $part) {
							if ($key == $purchase['item_id']) {
								for ($i=1; $i <= count($part['item_id']) ; $i++) {
									if ($part['qty'][$i] > 0) {
										$purchaseDetailModel->create([
											'purchase_request_id' => (int) $purchaseModel->id,
											'item_id' => (int) $part['item_id'][$i],
											'equipment_id' => (int) $detail_parents->id,
											'qty_to_order' => (int) $part['qty'][$i]
										]);
									}
								}
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
		

		\Session::flash('success', 'New PR created');

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
		
		$branches = \Auth::user()->getBranchesByArea(request()->corpID);
		
		if (\Auth::user()->checkAccessById(58 , 'E')) {
			if ($purchase->flag == 1) {
				return view('purchases.detailPO', [
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} else if ($purchase->flag == 2) {
				return view('purchases.edit', [
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} else if ($purchase->flag == 4) {
				return view('purchases.MarkForPO',[
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} else if ($purchase->flag == 5) {
				return view('purchases.verify',[
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} else if ($purchase->flag == 6) {
				return view('purchases.MarkForPO',[
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} else if ($purchase->flag == 7) {
				return view('purchases.detailPO',[
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} 
		} 
	
		if (\Auth::user()->checkAccessById(59 , 'E')) {
			if ($purchase->flag == 1) {
				return view('purchases.detailPO', [
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} else if ($purchase->flag == 2) {
				return view('purchases.MarkForPO',[
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);  
			} else if ($purchase->flag == 4) {
				return view('purchases.MarkForPO',[
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} else if ($purchase->flag == 5) {
				return view('purchases.MarkForPO',[
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} else if ($purchase->flag == 6) {
				return view('purchases.MarkForPO',[
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} else if ($purchase->flag == 7) {
				return view('purchases.detailPO',[
					'purchase' => $purchase, 
					'branches' => $branches, 
					]);
			} 
		}

		\Session::flash('error', "You don't have permission"); 
		return redirect("/home"); 
	}

	public function update(Request $request, $id)
	{
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseRequest;
		$purchaseModel->setConnection($company->database_name);
		
		$branches = \Auth::user()->getBranchesByArea(request()->corpID);

		$purchase_item = $purchaseModel->findOrFail($id);
		
		if(\Auth::user()->checkAccessById(58, 'E')) {
			$company = Corporation::findOrFail(request()->corpID);
			$purchasedetailModel = new \App\Models\Purchase\PurchaseDetail;
			$purchasedetailModel->setConnection($company->database_name);
			
			if ($purchase_item->flag == 2) {
				if (request()->updated) {
					if ($purchase_item->flag == 2) {
						$purchase_item->update([
							'branch' => $request->branch,
							'description' => $request->description,
							'total_qty' => $request->total_qty
						]);
					
						$purchase_item->request_details()->delete();
							
						if ($purchase_item->eqp_prt == 'equipment') {
							if (is_array(request()->purchases)) {
								$purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
								$purchaseDetailModel->setConnection($company->database_name);
								foreach (request()->purchases as $purchase) {
									$detail_parents = $purchaseDetailModel->create([
										'purchase_request_id' => $purchase_item->id,
										'item_id' => $purchase['item_id']
									]);
						
									if (request()->parts) {
										foreach (request()->parts as $key => $part) {
											if ($key == $purchase['item_id']) {
												if (is_array($part['item_id'])) {
													for ($i=1; $i <= count($part['item_id']) ; $i++) { 
														if ($part['qty'][$i] > 0) {
															$purchaseDetailModel->create([
																'purchase_request_id' => (int) $purchase_item->id,
																'item_id' => (int) $part['item_id'][$i],
																'equipment_id' => (int) $detail_parents->id,
																'qty_to_order' => (int) $part['qty'][$i]
															]);
														}
													}
												} else {
													if ($part['qty'] > 0) {
														$purchaseDetailModel->create([
															'purchase_request_id' => (int) $purchase_item->id,
															'item_id' => (int) $part['item_id'],
															'equipment_id' => (int) $detail_parents->id,
															'qty_to_order' => (int) $part['qty']
														]);
													}
												}
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
		
						\Session::flash('success', 'Purchase #'.$purchase_item->id.' has been updated');
						
						return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
					}
				}

				if (request()->parts) {
					if ($purchase_item->eqp_prt == 'equipment' ) {
						foreach (request()->parts as $key => $part) {
							foreach ($part as $key => $row) {
								$purchase = $purchasedetailModel->findOrFail($key);
								$purchase->update([
									'vendor_id' => $row['vendor_id'],
									'cost' => $row['cost']
								]);
								if ($row['qty_to_order'] != $purchase->qty_to_order) {
									$purchase_item->update([
										'flag' => 5,
									]);
									$purchase->update([
										'vendor_id' => $row['vendor_id'],
										'qty_old' => $purchase->qty_to_order,
										'qty_to_order' => $row['qty_to_order'],
										'cost' => $row['cost'],
										'isVerified' => 2
									]);
								} 
							}
						}
						if ($purchase_item->flag == 5) {
							\Session::flash('success', 'PR#['.$purchase_item->id.'] has been marked as “For Verification”, requires verification from requester.');
			
							return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
						}
					} else if ($purchase_item->eqp_prt == 'parts') 
					{
						foreach (request()->parts as $part) {
							$purchase = $purchasedetailModel->findOrFail($part['part_id']);
							$purchase->update([
								'vendor_id' => $part['vendor_id'],
								'cost' => $part['cost']
							]);
							if ($purchase->qty_to_order != $part['qty_to_order']) {
								$purchase_item->update([
									'flag' => 5
								]);
								$purchase->update([
									'vendor_id' => $part['vendor_id'],
									'qty_old' => $purchase->qty_to_order,
									'qty_to_order' => $part['qty_to_order'],
									'cost' => $part['cost'],
									'isVerified' => 2
								]);
							} 
						}
						if ($purchase_item->flag == 5) {
							\Session::flash('success', 'PR#['.$purchase_item->id.'] has been marked as “For Verification”, requires verification from requester.');
			
							return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
						}
					}
				} 
	
				$purchase_item->update([
					'items_changed' => $purchase_item->request_details ? count($purchase_item->request_details->whereIn('isVerified', [1,2])) : ''
				]);
	
				if ($purchase_item->flag == 2) {	
					$purchase_item->update([
						'flag' => 1,
						'date_approved' => date('Y-m-d'),
						'approved_by' => \Auth::user()->UserID,
					]);
				}
	
				\Session::flash('success', 'Purchase #'.$purchase_item->id.' has been updated');
			
				return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
			}
			if ( $purchase_item->flag == 5 ) {
				if (request()->delete_request && request()->parts) {
					$purchase_item->request_details()->delete();
					$purchase_item->delete();

					\Session::flash('success', 'Purchase #'.$purchase_item->id.' has been deleted');
			
					return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
				}
				
				if (request()->parts) {
					$company = Corporation::findOrFail(request()->corpID);
					$purchasedetailModel = new \App\Models\Purchase\PurchaseDetail;
					$purchasedetailModel->setConnection($company->database_name);
		
					$sumCost = 0;
					
					foreach (request()->parts as $key => $part) {
						$purchase_part = $purchasedetailModel->findOrFail($key);
						$sumCost += $purchase_part->cost*$part['qty'];
						$purchase_part->update([
							'date_verified' => date('Y-m-d')
						]);
					}

					$purchase_item->update([
						'flag' => 2,
						'status' => 'verified',
						'total_qty' => request()->total_qty,
						'total_cost' => $sumCost
					]);
				
					$purchase_item->request_details()->where('isVerified', 1)->delete();
					
					\Session::flash('success', 'PR# ['.$purchase_item->id.'] has been verified and is marked as “Request');
				
					return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
				} else {
					\Session::flash('success', 'PR# ['.$purchase_item->id.'] has been verified and is marked as “Request');
				
					return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
				} 	
			}		
		}

		if(\Auth::user()->checkAccessById(59, 'E')) {
			if ($purchase_item->flag == 2 && request()->remarks) {
				$purchase_item->update([
					'flag' => 4,
					'date_disapproved' => date('Y-m-d'),
					'disapproved_by' => \Auth::user()->UserID,
					'remarks' => request()->remarks
				]);
				
				\Session::flash('success', 'PR#['.$purchase_item->id.'] has been disapproved');
				
				return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
			} else if($purchase_item->flag == 5) {
				\Session::flash('success', 'PR#['.$purchase_item->id.'] has been marked as “For Verification”, requires verification from requester');
				
				return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
			}
			// else if ($purchase_item->flag == 2) {
			// 	$purchase_item->update([
			// 		'total_qty' => $request->total_qty
			// 	]);
			
			// 	$purchase_item->request_details()->delete();
					
			// 	if ($purchase_item->eqp_prt == 'equipment') {
			// 		if (is_array(request()->parts)) {
			// 			$purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
			// 			$purchaseDetailModel->setConnection($company->database_name);
			// 			foreach (request()->purchases as $purchase) {
			// 				$detail_parents = $purchaseDetailModel->create([
			// 					'purchase_request_id' => $purchase_item->id,
			// 					'item_id' => $purchase['item_id']
			// 				]);
							
			// 				foreach (request()->parts as $key => $part) {
			// 					if ($key == $purchase['item_id']) {
			// 						for ($i=1; $i <= count($part['item_id']) ; $i++) { 
			// 							$purchaseDetailModel->create([
			// 									'purchase_request_id' => (int) $purchase_item->id,
			// 									'item_id' => (int) $part['item_id'][$i],
			// 									'equipment_id' => (int) $detail_parents->id,
			// 									'qty_to_order' => (int) $part['qty'][$i]
			// 								]);
			// 						}
			// 					}
			// 				}
			// 			}
			// 		}	else {
			// 			$purchase_item->request_details->each->delete();
			// 		}
			// 	} else if ($purchase_item->eqp_prt == 'parts') {
			// 		if (is_array(request()->purchases)) {
			// 			$purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
			// 			$purchaseDetailModel->setConnection($company->database_name);
			// 			foreach (request()->purchases as $purchase) {
			// 				$detail_parents = $purchaseDetailModel->create([
			// 					'purchase_request_id' => (int)$purchase_item->id,
			// 					'item_id' => $purchase['item_id'],
			// 					'qty_to_order' => (int) $purchase['qty']
			// 				]);
			// 			}
			// 		} else {
			// 			$purchase_item->request_details->each->delete();
			// 		}
			// 	}

			// 	\Session::flash('success', 'Purchase #'.$purchase_item->id.' has been updated');
				
			// 	return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
			// }
		}
	}

	public function destroy($id)
	{
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseRequest;
		$purchaseModel->setConnection($company->database_name);

		$purchase = $purchaseModel->findOrFail($id);
		
		$purchase->request_details()->delete();

		$purchase->delete();
		
		\Session::flash('success', 'Purchase #'.$purchase->id.' has been cancelled and deleted');
	}

	public function getBrands()
	{  
		if (request()->radio == 'equipment') {
				$hdrModel = new \App\Models\Equip\Hdr;

				$items = $hdrModel->orderBy('description','asc')->get();

				return view('purchases.searchEQP', [
						'items' => $items
						]);
		} else if (request()->radio == 'parts') {
				$item_masterModel = new \App\Models\Item\Master;
				
				$itemparts = $item_masterModel->orderBy('description','asc')->get();
				
				return view('purchases.searchPRT', [
						'itemparts' => $itemparts
						]);
		}
	}

	public function getParts() {
		$detailModel = new \App\Models\Equip\Detail;

		$items = $detailModel->where('asset_id', request()->equipmentID)->orderBy('item_id')->distinct()->get();
		
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
			'isVerified' => 1,
			'remark' => request()->reason
		]);
		
		$purchase_item->purchaseRequest->update(['flag' => 5]);
	}

	public function destroyPart() {
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseDetail;
		$purchaseModel->setConnection($company->database_name);
		
		$purchase = $purchaseModel->findOrFail(request()->partID);
		$purchase->update([
			'isVerified' => 1
		]);
	}

	public function changeQTY() {
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseDetail;
		$purchaseModel->setConnection($company->database_name);

		$purchase_item = $purchaseModel->findOrFail(request()->partID);

		if ($purchase_item->date_verified) {
			$purchase_item->update([
				'qty_old' => $purchase_item->qty_to_order,
				'qty_to_order' => request()->qty
			]);
		} else {
			$purchase_item->update([
				'isVerified' => 2,
				'qty_old' => $purchase_item->qty_to_order,
				'qty_to_order' => request()->qty
			]);
	
			$purchase_item->purchaseRequest->update([
				'flag' => 5
			]);
		}
	}

	public function undoQTY() {
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseDetail;
		$purchaseModel->setConnection($company->database_name);

		$purchase_item = $purchaseModel->findOrFail(request()->partID);
		
		if ($purchase_item->purchaseRequest->flag == 5) {
			$purchase_item->update([
				'isVerified' => NULL,
				'qty_to_order' => $purchase_item->qty_old,
				'remark' => ''
			]);

			$count_item = count($purchaseModel->whereIn('isVerified', [1,2])->get());
		
			if ($count_item == 0) {
				$purchase_item->purchaseRequest->update([
					'flag' => 2
				]);
			}
		} else if ($purchase_item->purchaseRequest->flag == 2) {
			$purchase_item->update([
				'qty_to_order' => $purchase_item->qty_old,
				'remark' => ''
			]);
		}

		
	}

	public function undoDelete(){
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseDetail;
		$purchaseModel->setConnection($company->database_name);

		$purchase_item = $purchaseModel->findOrFail(request()->partID);
		
		$purchase_item->update([
			'isVerified' => '',
			'remark' => ''
		]);

		$count_item = count($purchaseModel->whereIn('isVerified', [1,2])->get());

		if ($count_item == 0) {
			$purchase_item->purchaseRequest->update([
				'flag' => 2
			]);
		}
	}
}
