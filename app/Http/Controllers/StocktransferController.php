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



use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StocktransferController extends Controller {
  public function index(Request $request) {
    $status= $request->status ? $request->status : 1;
    $company = Corporation::findOrFail($request->corpID);

    $delivery_data = Delivery::limit(1000)->get();

    return view('stocktransfer/index', [
      'corpID' => $request->corpID,
      'delivery_data' => $delivery_data,
      'status' => $status
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

      return view('stocktransfer/auto-item', [
        'items' => $items->get(),
        'corpID' => $request->corpID
      ]);
    }



    public function original(Request $request, $id) {
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
      'itemRows' => $itemRows
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

    public function markToserved($id){
        $tmaster = Tmaster::where( 'po_no',$id)->update([
            'served'=>1
        ]);   
        return response()->json(array('msg'=>'success'), 200);   
    }

    public function create(Request $request) {
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'A')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }
    $company = Corporation::findOrFail($request->corpID);
    $stockModel = new \App\Stock;
    $stockModel->setConnection($company->database_name);
    $purchaseOrderModel = new \App\PurchaseOrder;
    $purchaseOrderModel->setConnection($company->database_name);
    $retailID = StockType::where('type_desc', 'Retail')->first()->inv_type;
    $typeID = [0,$retailID];

    $stockitems = StockItem::where( 'Active', 1 )->whereIn('Type', $typeID)->orderBy('ItemCode')->get();
    $vendors = Vendor::orderBy('VendorName')->get();

    $brands = Brand::all();

    $prod_lines = ProductLine::all();

    $prod_lines = $prod_lines->map(function ($prod_lines) {
      return $prod_lines->Product;
    });
    $brands = $brands->map(function ($brands) {
      return $brands->Brand;
    });
    // dd(Brand::all());
    $pos = $purchaseOrderModel->where('served', 0)->orderBy('po_no', 'desc')->get();
    return view('stocktransfer.create',
      [
        'brands' => $brands,
        'prod_lines' => $prod_lines,
        'corpID' => $request->corpID,
        'vendors' => $vendors,
        'pos' => $pos,
        'stockitems' => $stockitems
      ]
    )->with('corpID', $request->corpID);
  }

    public function store(Request $request)
    {
        //
    }

   
    public function show(Request $request, $id) {
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
}
