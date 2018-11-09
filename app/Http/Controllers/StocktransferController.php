<?php

namespace App\Http\Controllers;

use App\Tmaster;
use App\Spodetail;
use App\Delivery;

use App\Stock;
use App\StockItem;
use App\StockType;
use App\StockDetail;
use App\Srcvdetail;

use App\Stxfrdetail;

use App\Vendor;
use App\PurchaseOrder;
use App\PurchaseOrderDetail;
use App\Corporation;
use App\Brand;
use App\ProductLine;
use DB;
use Validator;
use Datetime;
use App\Transformers\Stxfr\DetailTransformer;
use App\Transformers\Spo\HdrTransformer;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StocktransferController extends Controller {
  public function index(Request $request) {
    $status= $request->status ? $request->status : 1;
    $stockStatus = $request->stockStatus ? $request->stockStatus : 1;
    $tab = $request->tab ? $request->tab : 'auto';

    return view('stocktransfer/index', [
      'corpID' => $request->corpID,
      'status' => $status,
      'stockStatus' => $stockStatus,
      'tab' => $tab
    ]);
  }

    public function autoItems(Request $request) {
      $company = Corporation::findOrFail($request->corpID);
      $status= $request->status ? $request->status : 1;

      $hdrModel = new \App\Models\Spo\Hdr;
      $hdrModel->setConnection($company->database_name);

      $items = $hdrModel->orderBy('po_no', 'DESC');

      if($status == 1) {
          $items = $items->where('served', 0);
      }else if($status == 2) {
        $items = $items->where('served', 1);
      }

        $items = $items->get();

        return fractal($items, new HdrTransformer)->toJson();
    }

    public function deliveryItems(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);

        $detailModel = new \App\Models\Stxfr\Hdr;
        $detailModel->setConnection($company->database_name);

        $items = $detailModel->orderBy('Txfr_ID', 'DESC');

        switch($request->stockStatus) {
            case 1:
                $items = $items->where('Rcvd', 0);
                break;
            case 2:
                $items = $items->where('Rcvd', 1);
                break;
            default:
                break;
        }

        $items = $items->get();

        return fractal($items, new DetailTransformer)->toJson();
    }

    public function original(Request $request, $id) {
        if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 42, 'V')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

      $company = Corporation::findOrFail($request->corpID);
      
      $detailModel = new \App\Models\Spo\Detail;
      $detailModel->setConnection($company->database_name);

      $hdrModel = new \App\Models\Spo\Hdr;
      $hdrModel->setConnection($company->database_name);

      $stockItem = $hdrModel->findOrFail($id);

      $branchIds = $stockItem->items->pluck('Branch');
      $branches = \App\Branch::whereIn('Branch', $branchIds);

      $itemRows = $stockItem->items()
                            ->whereIn('Branch', $branches->pluck('Branch'))
                            ->orderBy('ItemCode', 'ASC')
                            ->get()
                            ->groupBy('ItemCode');
  
    return view('stocktransfer/original', [
      'stockItem' => $stockItem,
      'branches' => $branches->get(),
      'itemRows' => $itemRows,
      'corpID' => $request->corpID
    ]);
   
    }

    public function transfer(Request $request, $id)
    {
        $company = Corporation::findOrFail($request->corpID);

        $hdrModel = new \App\Models\Stxfr\Hdr;
        $hdrModel->setConnection($company->database_name);

        $detailModel = new \App\Models\Stxfr\Detail;
        $detailModel->setConnection($company->database_name);

        $rcvModel = new \App\Srcvdetail;
        $rcvModel->setConnection($company->database_name);

        $spoModel = new \App\Models\Spo\Hdr;
        $spoModel->setConnection($company->database_name);

        $stockItem = $spoModel->findOrFail($id);

        if($request->items) {
            foreach($request->items as $itemCode => $branches) {
                foreach($branches as $branch => $itemParams) {
                    if($itemParams['Qty'] == 0) {
                        continue;
                    }

                    $hdrItem = $hdrModel->create([
                        'Txfr_Date' => date('Y-m-d'),
                        'Txfr_To_Branch' => $branch,
                        'Rcvd' => 0,
                        'Uploaded' => 0
                    ]);

                    $rcvItems = $rcvModel->where('item_id', $itemParams['ItemId'])
                                    ->where('Bal', '>', 0)
                                    ->orderBy('RcvDate', 'ASC')
                                    ->get();

                    $itemQtyRemaining = $itemParams['Qty'];
                    foreach($rcvItems as $rcvItem) {
                        $itemQty = $itemQtyRemaining;
                        $itemQtyRemaining -= $rcvItem->Bal;
                        if($itemQty <= $rcvItem->Bal) {
                            $rcvItem->update(['Bal' => $rcvItem->Bal - $itemQty]);
                        }else {
                            $itemQty = $rcvItem->Bal;
                            $rcvItem->update(['Bal' => 0]);
                        }

                        $detailModel->create([
                            'Txfr_ID' => $hdrItem->Txfr_ID,
                            'item_id' => $itemParams['ItemId'],
                            'ItemCode' =>$itemCode,
                            'Qty' => $itemQty,
                            'Bal' => $itemQty,
                            'Movement_ID' => $rcvItem->Movement_ID,
                        ]);

                        if($itemQtyRemaining <= 0) {
                            break;
                        }
                    }

                    $poItems = $stockItem->items()
                                         ->whereRaw('ServedQty < Qty')
                                         ->where('item_id', $itemParams['ItemId'])
                                         ->where('Branch', $branch)
                                         ->get();

                    $itemQtyRemaining = $itemParams['Qty'];
                    foreach($poItems as $poItem) {
                        $itemQty = $itemQtyRemaining;
                        $itemQtyRemaining -= $poItem->Qty - $poItem->ServedQty;

                        if($itemQty <= $poItem->Qty - $poItem->ServedQty) {
                            $poItem->update(['ServedQty' => $poItem->ServedQty + $itemQty]);
                        }else {
                            $poItem->update(['ServedQty' => $poItem->Qty]);
                        }

                        if($itemQtyRemaining <= 0) {
                            break;
                        }
                    }
                }
            }
        }

      return response()->json([
        'success'=> 'success'
      ]);
    }

    public function markToServed(Request $request, $id){
        $company = Corporation::findOrFail($request->corpID);

        $spoModel = new \App\Models\Spo\Hdr;
        $spoModel->setConnection($company->database_name);

        $stockItem = $spoModel->findOrFail($id);

        $stockItem->update(['served' => 1]);

        return response()->json([
            'success'=> 'success'
        ]);
    }

    public function create(Request $request)
    {
        if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 42, 'A')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $company = Corporation::findOrFail($request->corpID);

        $hdrModel = new \App\Models\Stxfr\Hdr;
        $hdrModel->setConnection($company->database_name);

        $cfgModel = new \App\Models\SItem\Cfg;
        $cfgModel->setConnection($company->database_name);

        $rcvModel = new \App\Srcvdetail;
        $rcvModel->setConnection($company->database_name);

        $purchaseOrderModel = new \App\PurchaseOrder;
        $purchaseOrderModel->setConnection($company->database_name);

        $suggestItems = $cfgModel->where('Active', 1)
                                ->orderBy('ItemCode', 'ASC')
                                ->distinct()
                                ->get();

        $branches = $company->branches()->where('Active', 1)
                            ->orderBy('ShortName', 'ASC')
                            ->get();

        return view('stocktransfer.create', [
            'branches' => $branches,
            'corpID' => $request->corpID,
            'suggestItems' => $suggestItems,
            'rcvModel' => $rcvModel
        ]);
    }

    public function edit(Request $request, $id)
    {
        if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 42, 'E')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $company = Corporation::findOrFail($request->corpID);

        $hdrModel = new \App\Models\Stxfr\Hdr;
        $hdrModel->setConnection($company->database_name);

        $hdrItem = $hdrModel->findOrFail($id);

        $cfgModel = new \App\Models\SItem\Cfg;
        $cfgModel->setConnection($company->database_name);

        $rcvModel = new \App\Srcvdetail;
        $rcvModel->setConnection($company->database_name);

        $suggestItems = $cfgModel->where('Active', 1)
                                ->distinct()
                                ->orderBy('ItemCode', 'ASC')
                                ->get();


        $branches = $company->branches()->where('Active', 1)
                            ->orderBy('ShortName', 'ASC')
                            ->get();

        return view('stocktransfer.edit', [
            'branches' => $branches,
            'corpID' => $request->corpID,
            'suggestItems' => $suggestItems,
            'hdrItem' => $hdrItem,
            'rcvModel' => $rcvModel,
            'stockStatus' => $request->stockStatus
        ]);
    }

    public function store(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);

        $hdrModel = new \App\Models\Stxfr\Hdr;
        $hdrModel->setConnection($company->database_name);

        $rcvModel = new \App\Srcvdetail;
        $rcvModel->setConnection($company->database_name);

        $hdrParams = [
            'Txfr_Date' => $request->Txfr_Date,
            'Txfr_To_Branch' => $request->Txfr_To_Branch,
            'Rcvd' => 0,
            'Uploaded' => 0
        ];


        $hdrItem = $hdrModel->create($hdrParams);

        if($request->details) {
            foreach($request->details as $itemParams) {
                $rcvItems = $rcvModel->where('item_id', $itemParams['item_id'])
                                    ->where('Bal', '>', 0)
                                    ->orderBy('RcvDate', 'ASC')
                                    ->get();

                $itemQtyRemaining = $itemParams['Qty'];
                foreach($rcvItems as $rcvItem) {
                    $itemQty = $itemQtyRemaining;
                    $itemQtyRemaining -= $rcvItem->Bal;
                    if($itemQty <= $rcvItem->Bal) {
                        $rcvItem->update(['Bal' => $rcvItem->Bal - $itemQty]);
                    }else {
                        $itemQty = $rcvItem->Bal;
                        $rcvItem->update(['Bal' => 0]);
                    }

                    $hdrItem->details()->create([
                        'item_id' => $itemParams['item_id'],
                        'ItemCode' => $itemParams['ItemCode'],
                        'Qty' => $itemQty,
                        'Bal' => $itemQty,
                        'Movement_ID' => $rcvItem->Movement_ID,
                    ]);

                    if($itemQtyRemaining <= 0) {
                        break;
                    }
                }
            }
        }

        \Session::flash('success', "Stock item has been created successfully"); 

        return redirect(route('stocktransfer.index', [
            'corpID' => $request->corpID,
            'tab' => 'stock'
        ]));
    }


    public function update(Request $request, $id)
    {
        $company = Corporation::findOrFail($request->corpID);

        $hdrModel = new \App\Models\Stxfr\Hdr;
        $hdrModel->setConnection($company->database_name);

        $rcvModel = new \App\Srcvdetail;
        $rcvModel->setConnection($company->database_name);

        $hdrItem = $hdrModel->findOrFail($id);

        $hdrItem->update($request->only([
            'Txfr_Date', 'Txfr_To_Branch'
        ]));

        foreach($hdrItem->details as $detail) {
            $rcvItem = $rcvModel->where('Movement_ID', $detail->Movement_ID)
                            ->first();
            if($rcvItem) {
                $rcvItem->update(['Bal' => $rcvItem->Bal + $detail->Bal]);
            }
        }
        $hdrItem->details()->delete();

        if($request->details) {
            foreach($request->details as $itemParams) {
                $rcvItems = $rcvModel->where('item_id', $itemParams['item_id'])
                                    ->where('Bal', '>', 0)
                                    ->orderBy('RcvDate', 'ASC')
                                    ->get();
                $itemQtyRemaining = $itemParams['Qty'];
                foreach($rcvItems as $rcvItem) {
                    $itemQty = $itemQtyRemaining;
                    $itemQtyRemaining -= $rcvItem->Bal;
                    if($itemQty <= $rcvItem->Bal) {
                        $rcvItem->update(['Bal' => $rcvItem->Bal - $itemQty]);
                    }else {
                        $itemQty = $rcvItem->Bal;
                        $rcvItem->update(['Bal' => 0]);
                    }
                    $hdrItem->details()->create([
                        'item_id' => $itemParams['item_id'],
                        'ItemCode' => $itemParams['ItemCode'],
                        'Qty' => $itemQty,
                        'Bal' => $itemQty,
                        'Movement_ID' => $rcvItem->Movement_ID,
                    ]);
                    if($itemQtyRemaining <= 0) {
                        break;
                    }
                }
            }
        }


        \Session::flash('success', "Stock item has been updated successfully"); 

        return redirect(route('stocktransfer.index', [
            'corpID' => $request->corpID,
            'tab' => 'stock',
            'stockStatus' => $request->stockStatus
        ]));
    }

   
    public function show(Request $request, $id) {
        if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 42, 'V')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

      $company = Corporation::findOrFail($request->corpID);
      
      $hdrModel = new \App\Models\Spo\Hdr;
      $hdrModel->setConnection($company->database_name);

      $stockItem = $hdrModel->findOrFail($id);
      $branchIds = $stockItem->items->pluck('Branch');

      $branches = \App\Branch::whereIn('Branch', $branchIds);

      $itemRows = $stockItem->items()
                            ->whereIn('Branch', $branches->pluck('Branch'))
                            ->orderBy('ItemCode', 'ASC')
                            ->get()
                            ->groupBy('ItemCode');

      return view('stocktransfer/show', [
        'stockItem' => $stockItem,
        'branches' => $branches->get(),
        'itemRows' => $itemRows,
        'corpID' => $request->corpID
      ]);
    }

    public function destroy(Request $request, $id)
    {
        $company = Corporation::findOrFail($request->corpID);

        $hdrModel = new \App\Models\Stxfr\Hdr;
        $hdrModel->setConnection($company->database_name);

        $rcvModel = new \App\Srcvdetail;
        $rcvModel->setConnection($company->database_name);

        $hdrItem = $hdrModel->findOrFail($id);

        foreach($hdrItem->details as $detail) {
            $rcvItem = $rcvModel->where('Movement_ID', $detail->Movement_ID)
                            ->first();

            if($rcvItem) {
                $rcvItem->update(['Bal' => $rcvItem->Bal + $detail->Bal]);
            }
        }

        $hdrItem->details()->delete();

        $hdrItem->delete();

        return response()->json([
            'success'=> true
        ]);
    }
}
