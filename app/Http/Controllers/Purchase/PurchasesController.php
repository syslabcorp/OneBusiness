<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use App\Company;
use Illuminate\Http\Request;
use App\Models\Corporation;

class PurchasesController extends Controller
{
    public function index()
    {
        if(!\Auth::user()->checkAccessById(56, 'V')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $companies = Corporation::orderBy('corp_name')
                                ->where('database_name', '!=', '')
                                ->get();
        if (request()->corpID) {
            $company = Corporation::findOrFail(request()->corpID);
        } else {
            $company = $companies->first();
        }
     
        return view('purchases.index', [
            'companies' => $companies,
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
        
        $branches = \Auth::user()->getBranchesByArea(request()->corpID);
        $purchase = new \App\Models\Purchase\PurchaseRequest;
        $purchase->setConnection($company->database_name);
     
        return view('purchases.create', [
            'branches' => $branches,
            'user_id' => \Auth::user()->UserID,
            'purchase' => $purchase
        ]);
    }

    public function store(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);
        $purchaseModel = new \App\Models\Purchase\PurchaseRequest;
        $purchaseModel->setConnection($company->database_name);
        
        $purchaseParams = request()->only([
            'requester_id', 'branch', 'description', 'date', 'total_qty'
        ]);

        $purchaseParams['date'] = date_create(request()->date) ?? date('Y-m-d');
        
        $purchaseModel = $purchaseModel->create($purchaseParams);
  
        if (is_array(request()->purchases)) {
            $purchaseDetailModel = new \App\Models\Purchase\PurchaseDetail;
            $purchaseDetailModel->setConnection($company->database_name);
            foreach (request()->purchases as $purchasedetail) {
                $purchaseDetailModel->create([
                    'purchase_request_id' => $purchaseModel->id,
                    'eqp' => isset($purchasedetail['eqp']) ? $purchasedetail['eqp'] : 0,
                    'prt' => isset($purchasedetail['prt']) ? $purchasedetail['prt'] : 0,
                    'item_name' => $purchasedetail['item_name'],
                    'qty_to_order' => $purchasedetail['qty_to_order']
                ]);
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

    public function edit(Request $request, $corpID) 
    {
        $company = Corporation::findOrFail($corpID);
        $purchaseModel = new \App\Models\Purchase\PurchaseRequest;
        $purchaseModel->setConnection($company->database_name);

        $purchase = $purchaseModel->find($request->requestID);

        $branches = \Auth::user()->getBranchesByArea($corpID);
        // dd($branches);
        return view('purchases.edit', ['purchase' => $purchase, 'branches' => $branches]);
    }

    public function update(Request $request, $id)
    {
        // if(!\Auth::user()->checkAccessById(56, 'E')) {
        //     \Session::flash('error', "You don't have permission"); 
        //     return redirect("/home"); 
        // }
        dd($request);
        // $company = Corporation::findOrFail(request()->corpID);

        // $equipment = \App\Models\Equip\Hdr::findOrFail($id);

        // $equipParams = request()->only([
        //     'description', 'type', 'jo_dept'
        // ]);

        // $equipParams['isActive'] = request()->active ? 1 : 0;

        // $equipment->update($equipParams);

        // $equipment->details()->delete();
       
        // if (is_array(request()->parts)) {
        //     foreach (request()->parts as $partParams) {
        //         if (!empty($partParams['item_id'])) {
        //             \App\Models\Equip\Detail::create([
        //                 'asset_id' => $equipment->asset_id,
        //                 'item_id' => $partParams['item_id'],
        //                 'qty' => $partParams['qty']
        //             ]);

        //             \App\Models\Item\Master::where('item_id', '=', $partParams['item_id'])
        //                 ->update([
        //                     'LastCost' => $partParams['lastcost']
        //                 ]);
        //         } 
        //     }
        // } else {
        //     $equipment->details->each->delete();
        // }

        // \Session::flash('success', 'Equipment # has been updated');
        
        return redirect(route('purchase_request.index', ['corpID' => request()->corpID]));
    }
}
