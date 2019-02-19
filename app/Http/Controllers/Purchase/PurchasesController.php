<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Company;
use Illuminate\Http\Request;
use App\Models\Corporation;
use App\Http\Controllers\Purchase\EquipTransformer;
use Carbon\Carbon;

class PurchasesController extends Controller
{
	public function index()
	{
		if (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, 'V') || \Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'V')) {
			$company = Corporation::findOrFail(request()->corpID);
	
			return view('purchases.index', [
					'company' => $company,
					'corpID' => request()->corpID
			]);
		} else {
			\Session::flash('error', "You don't have permission"); 
			return redirect("/home");
		}
	}

	public function create()
	{
		if (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, 'A') || \Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'A')) {
			$company = Corporation::findOrFail(request()->corpID);
			
			$branches = \Auth::user()->getBranchesByGroup(request()->corpID);
	
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
		if(!\Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, 'A')) {
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

		if ($purchaseParams['eqp_prt'] == 'Equipment') {
			$sumcost = 0;
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
										$item_master = \App\Models\Item\Master::find($part['item_id'][$i]);
										$purchaseDetailModel->create([
											'purchase_request_id' => (int) $purchaseModel->id,
											'item_id' => (int) $part['item_id'][$i],
											'equipment_id' => (int) $detail_parents->id,
											'qty_to_order' => (int) $part['qty'][$i],
											'cost' => $item_master ? $item_master->LastCost : NULL
										]);
										$sumcost += $item_master ? $item_master->LastCost*$part['qty'][$i] : 0;
									}
								}
							}
						}
					} 
				}

				$updatePR = $purchaseModel->update([
					'total_cost' => $sumcost
				]);
			}
		} else if ($purchaseParams['eqp_prt'] == 'Part') {
			$sumcost = 0;
			if (is_array(request()->purchases)) {
				$purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
				$purchaseDetailModel->setConnection($company->database_name);
				foreach (request()->purchases as $purchase) {
					$item_master = \App\Models\Item\Master::find($purchase['item_id']);
					$detail_parents = $purchaseDetailModel->create([
						'purchase_request_id' => $purchaseModel->id,
						'item_id' => $purchase['item_id'],
						'qty_to_order' => (int) $purchase['qty'],
						'cost' => $item_master ? $item_master->LastCost : NULL
					]);
					$sumcost += $item_master ? $item_master->LastCost*$purchase['qty'] : 0;
				}
				$updatePR = $purchaseModel->update([
					'total_cost' => $sumcost
				]);
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
		
		$branches = \Auth::user()->getBranchesByGroup(request()->corpID);

		if (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, 'V')) {
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
	
		if (\Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'V')) {
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
				return view('purchases.ViewDetailMarkForPO',[
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

		$purchase_item = $purchaseModel->findOrFail($id);

		if(\Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, 'E')) {
			$company = Corporation::findOrFail(request()->corpID);
			$purchasedetailModel = new \App\Models\Purchase\PurchaseDetail;
			$purchasedetailModel->setConnection($company->database_name);
	
			if ($purchase_item->flag == 2) {
				if (request()->updated) {
					if ($purchase_item->flag == 2) {
						$purchase_item->request_details()->delete();
							
						if ($purchase_item->eqp_prt == 'Equipment') {
							$sumcost = 0;
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
															$item_master = \App\Models\Item\Master::find($part['item_id'][$i]);
															$purchaseDetailModel->create([
																'purchase_request_id' => (int) $purchase_item->id,
																'item_id' => (int) $part['item_id'][$i],
																'equipment_id' => (int) $detail_parents->id,
																'qty_to_order' => (int) $part['qty'][$i],
																'cost' => $item_master ? $item_master->LastCost : NULL
															]);
															$sumcost += $item_master ? $item_master->LastCost*$part['qty'][$i] : 0;
														}
													}
												} 
											}
										}
									}
								}
							}	else {
								$purchase_item->request_details->each->delete();
							}
						} else if ($purchase_item->eqp_prt == 'Part') {
							$sumcost = 0;
							if (is_array(request()->purchases)) {
								$purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
								$purchaseDetailModel->setConnection($company->database_name);
								foreach (request()->purchases as $purchase) {
									$item_master = \App\Models\Item\Master::find($purchase['item_id']);
									$detail_parents = $purchaseDetailModel->create([
										'purchase_request_id' => (int)$purchase_item->id,
										'item_id' => $purchase['item_id'],
										'qty_to_order' => (int) $purchase['qty'],
										'cost' => $item_master ? $item_master->LastCost : NULL
									]);
									$sumcost += $item_master ? $item_master->LastCost*$purchase['qty'] : 0;
								}
							} else {
								$purchase_item->request_details->each->delete();
							}
						}

						$purchase_item->update([
							'branch' => $request->branch,
							'description' => $request->description,
							'total_qty' => $request->total_qty,
							'total_cost' => $sumcost
						]);

						\Session::flash('success', 'Purchase #'.$purchase_item->id.' has been updated');
						
						return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
					}
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
					$sumCost = 0;
					
					$purchase_item->request_details()->where('isVerified', 1)->delete();
					
					if ($purchase_item->eqp_prt == 'Part') {
						if (count($purchase_item->request_details()->whereNull('isVerified')->orWhere('isVerified', 2)->get()) == 0) {
							$purchase_item->delete();
							
							\Session::flash('success', 'PR# ['.$purchase_item->id.'] has been verified and is marked as “Request');
					
							return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
						} 
					} else if ($purchase_item->eqp_prt == 'Equipment') {
						if (count($purchase_item->request_details()->where('equipment_id','!=',NULL)->get()) == 0) {
							$purchase_item->request_details()->delete();
							$purchase_item->delete();

							\Session::flash('success', 'PR# ['.$purchase_item->id.'] has been verified and is marked as “Request');
					
							return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
						} else {
							$parts = $purchase_item->request_details()->where('equipment_id',NULL)->get();
							foreach ($parts as $part) {
								if (empty($part->parts->all())) {
									$part->delete();
								}
							}
						}
					}
				
					$item_part = $purchasedetailModel->where('isVerified', 2)->get();
		
					foreach ($item_part as $part) {
						$sumCost += $part->cost*$part->qty;
						$part->update([
							'isVerified' => 3,
							'date_verified' => date('Y-m-d')
						]);
					}

					$purchase_item->update([
						'flag' => 2,
						'status' => 'verified',
						'total_qty' => request()->total_qty,
						'total_cost' => $sumCost
					]);
				
					\Session::flash('success', 'PR# ['.$purchase_item->id.'] has been verified and is marked as “Request');
				
					return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
				} 
			}		
		}

		if(\Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'E')) {
			$company = Corporation::findOrFail(request()->corpID);
			$purchasedetailModel = new \App\Models\Purchase\PurchaseDetail;
			$purchasedetailModel->setConnection($company->database_name);

			if ($purchase_item->flag == 2 && request()->remarks) {
				$purchase_item->update([
					'flag' => 4,
					'date_disapproved' => date('Y-m-d'),
					'disapproved_by' => \Auth::user()->UserID,
					'remarks' => request()->remarks
				]);
				
				\Session::flash('success', 'PR#['.$purchase_item->id.'] has been disapproved');
				
				return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
			} else if($purchase_item->flag == 2 && request()->verification) {
				if (request()->parts) {
					if ($purchase_item->eqp_prt == 'Equipment' ) {
						foreach (request()->parts as $key => $part) {
							foreach ($part as $key => $row) {
								$purchase = $purchasedetailModel->findOrFail($key);
								$purchase->update([
									'vendor_id' => $row['vendor_id'],
									'cost' => $row['cost']
								]);
							}
						}
					} else if ($purchase_item->eqp_prt == 'Part') 
					{
						foreach (request()->parts as $part) {
							$purchase = $purchasedetailModel->findOrFail($part['part_id']);
							$purchase->update([
								'vendor_id' => $part['vendor_id'],
								'cost' => $part['cost']
							]);
						}
					}
				} 

				$purchase_item->update([
					'items_changed' => $purchase_item->request_details ? count($purchase_item->request_details->whereIn('isVerified', [1,2,3])) : '',
					'flag' => 5
				]);

				\Session::flash('success', 'PR#['.$purchase_item->id.'] has been marked as “For Verification”, requires verification from requester');
				
				return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
			} else if ($purchase_item->flag == 2) {
				if (request()->parts) {
					if ($purchase_item->eqp_prt == 'Equipment' ) {
						foreach (request()->parts as $key => $part) {
							foreach ($part as $key => $row) {
								$purchase = $purchasedetailModel->findOrFail($key);
								$purchase->update([
									'vendor_id' => $row['vendor_id'],
									'cost' => $row['cost']
								]);
							}
						}
					} else if ($purchase_item->eqp_prt == 'Part') 
					{
						foreach (request()->parts as $part) {
							$purchase = $purchasedetailModel->findOrFail($part['part_id']);
							$purchase->update([
								'vendor_id' => $part['vendor_id'],
								'cost' => $part['cost']
							]);
						}
					}
				} 
				
				$purchase_item->update([
					'total_cost' => request()->total_qty,
					'flag' => 1,
					'date_approved' => date('Y-m-d'),
					'approved_by' => \Auth::user()->UserID,
				]);

				\Session::flash('success', 'PR# ['.$purchase_item->id.'] has been approved for purchase order.');
				
				return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
			}
		}

		\Session::flash('error', "You don't have permission"); 
		return redirect("/home");
	}

	public function getBrands()
	{  
		if (request()->radio == 'Equipment') {
				$hdrModel = new \App\Models\Equip\Hdr;

				$items = $hdrModel->orderBy('description','asc')->get();

				return view('purchases.searchEQP', [
						'items' => $items
						]);
		} else if (request()->radio == 'Part') {
				$item_masterModel = new \App\Models\Item\Master;
				
				$itemparts = $item_masterModel->orderBy('description','asc')->get();
				
				return view('purchases.searchPRT', [
						'itemparts' => $itemparts
						]);
		}
	}

	public function getParts() {
		if (request()->EQP_PRT == 'Equipment') {
			$detailModel = new \App\Models\Equip\Detail;

			$items = $detailModel->where('asset_id', request()->equipmentID)->orderBy('item_id')->distinct()->get();
			
			return view('purchases.showEQP', [
					'items' => $items
			]);
		}
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
		
		// $purchase_item->purchaseRequest->update(['flag' => 5]);
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

		$purchase_item->update([
			'isVerified' => 2,
			'qty_old' => $purchase_item->qty_to_order,
			'qty_to_order' => request()->qty,
			'remark' => 'from ['.$purchase_item->qty_to_order.'] to ['.request()->qty.']'
		]);
	}

	public function undoQTY() {
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseDetail;
		$purchaseModel->setConnection($company->database_name);

		$purchase_item = $purchaseModel->findOrFail(request()->partID);
		
		if (($purchase_item->purchaseRequest->flag == 2) && $purchase_item->date_verified ) {
			$purchase_item->update([
				'isVerified' => 3,
				'qty_to_order' => $purchase_item->qty_old,
			]);
			
		}	else if ($purchase_item->purchaseRequest->flag == 2) {
			$purchase_item->update([
				'isVerified' => NULL,
				'qty_to_order' => $purchase_item->qty_old,
			]);
		}	
	}

	public function undoDelete(){
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseDetail;
		$purchaseModel->setConnection($company->database_name);

		$purchase_item = $purchaseModel->findOrFail(request()->partID);
		
		if (($purchase_item->purchaseRequest->flag == 2) && $purchase_item->date_verified ) {
			$purchase_item->update([
				'isVerified' => 3,
				'remark' => NULL
			]);
		} else if ($purchase_item->purchaseRequest->flag == 2) {
			$purchase_item->update([
				'isVerified' => NULL,
				'remark' => NULL
			]);
		}

		$count_item = count($purchaseModel->whereIn('isVerified', [1,2])->get());

		if ($count_item == 0) {
			$purchase_item->purchaseRequest->update([
				'flag' => 2
			]);
		}
	}

	public function accessPage() {
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseRequest;
		$purchaseModel->setConnection($company->database_name);
	
		$purchaseItem = $purchaseModel->findOrFail(request()->id);
		
		$purchaseItem->update([
			'is_editing_by' => \Auth::user()->UserID,
			'is_editing_at' => Carbon::now()
		]);
	}

	public function checkAccessID() {
		$company = Corporation::findOrFail(request()->corpID);
		$purchaseModel = new \App\Models\Purchase\PurchaseRequest;
		$purchaseModel->setConnection($company->database_name);

		$purchase = $purchaseModel->findOrFail(request()->id);
		
		if ($purchase->is_editing_by && $purchase->is_editing_by != \Auth::user()->UserID && Carbon::now()->diffInSeconds($purchase->is_editing_at) < 10) {
			return response([
				'success' => false 
			]); 
		} else {
			return response([
				'success' => true 
			]);
		}
	}

	public function checkDAVE() {
		$result = true;
		$array_DAVE = ['D','A','V','E'];
		
		foreach ($array_DAVE as $DAVE) {
			if (!\Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, $DAVE)) {
				$result = false ;
				break;
			}
		}

		return response()->json([
			'checkDAVE' => $result
		]);	
	}
}
