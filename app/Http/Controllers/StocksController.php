<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Stock;
use App\StockItem;
use App\StockType;
use App\StockDetail;
use App\Vendor;
use App\PurchaseOrder;
use App\PurchaseOrderDetail;
use App\Corporation;
use App\Brand;
use App\ProductLine;
use App\Spodetail;
use DB;
use Validator;
use Datetime;

class StocksController extends Controller
{
  public function show(Request $request)
  {
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'V')) {
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

    $stock = $stockModel->find($request->stock);
    $vendors = Vendor::orderBy('VendorName')->get();

  
    $pos = $purchaseOrderModel->where('served', 0)->get();
    return view('stocks.show',
      [
        'corpID' => $request->corpID,
        'stock' => $stock,
        'pos' => $pos,
        'vendors' => $vendors,
        'print' => $request->print
      ]
    )->with('corpID', $request->corpID);
  }

  public function update(Request $request)
  {
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'E')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }    
    $request->RcvDate = new Datetime($request->RcvDate);
    $company = Corporation::findOrFail($request->corpID);
    $stockModel = new \App\Stock;
    $stockModel->setConnection($company->database_name);
    
    $purchaseOrderDetailModel = new \App\PurchaseOrderDetail;
    $purchaseOrderDetailModel->setConnection($company->database_name);

    $stock = $stockModel->find($request->stock); 
    $stock->Supp_ID = $request->Supp_ID;
    $stock->RcvDate = $request->RcvDate;
    $stock->RcvdBy = \Auth::user()->UserID;
    $stock->DateSaved = date('Y-m-d H:i:s');
    $stock->TotalAmt = $request->total_amt;
    $stock->save();
    
    $stock->stock_details()->delete();
    
    if (is_array($request->stocks)) {
      foreach($request->stocks as $detail) {
        $stock_detail = new \App\StockDetail;
        $stock_detail->setConnection($company->database_name);
        $stock_detail->item_id = $detail['item_id'];
        $stock_detail->Qty = floatval($detail['qty']) ;
        $stock_detail->Bal = floatval($detail['qty']);
        $stock_detail->Cost = floatval($detail['cost']);
        $stock_detail->RcvDate = $request->RcvDate;
        $stock_detail->RR_No = $stock->RR_No;
        $stock_detail->save();

        $stock_item = StockItem::find($detail['item_id']);
        $stock_item->LastCost = $detail['cost'];
        $data = $stock_item->save();
      }
    }

    $stock->TotalAmt = floatval($request->total_amt);
    $stock->save();
    \Session::flash('success', "D.R #$stock->RR_No is successfully updated");
    return redirect()->route('stocks.show', [$stock, 'corpID' => $request->corpID, 'print' => $request->print]);
  }

  public function save_new_row_ajax(Request $request)
  {
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'A')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }
    $company = Corporation::findOrFail($request->corpID);
    $stockModel = new \App\Stock;
    $stockModel->setConnection($company->database_name);
    $stockDetailModel = new \App\StockDetail;
    $stockDetailModel->setConnection($company->database_name);
    $purchaseOrderDetailModel = new \App\PurchaseOrderDetail;
    $purchaseOrderDetailModel->setConnection($company->database_name);

    $stock = $stockModel->find($request->stock_id); 

    $success = false;
    
    if($request->item_id){
      $stock_detail = $stockDetailModel;
      $stock_detail->item_id = intval($request->item_id);
      //$stock_detail->ItemCode = $request->ItemCode;
      // $stock_detail->ServedQty = intval($request->ServedQty);
      $stock_detail->Qty = floatval($request->Qty);
      $stock_detail->Bal = floatval($request->Qty);
      $stock_detail->RR_No = $request->RR_No;
      $stock_detail->RcvDate = $request->RcvDate;
      $stock_detail->Cost = $request->Cost;
      $success = $stock_detail->save();
      
      $success = true;
      
      if($success && $request->po && ($request->po != ""))
      {
        $detail = $purchaseOrderDetailModel;
        $detail->item_id = intval($request->item_id);
        // $detail->ItemCode = $request->ItemCode;
        $detail->po_no = $request->po;
        $detail->Qty = floatval($request->Qty);
        $detail->ServedQty = intval($request->ServedQty);
        $detail->cost = $request->Cost;
        $detail->save();
      }
    }
    if($success == true)
    {
      return response()->json([
        'status' => true,
        'item_id' => $stock_detail->item_id,
        'ItemCode' => $stock_detail->stock_item->ItemCode,
        'ServedQty' => $stock_detail->ServedQty,
        'Qty' => $stock_detail->Qty,
        'Cost' => number_format( $stock_detail->Cost, 2),
        'Prod_Line' => $stock_detail->stock_item->product_line->Product,
        'Brand' => $stock_detail->stock_item->brand->Brand,
        'Description' => $stock_detail->stock_item->Description,
        'Unit' => $stock_detail->stock_item->Unit,
        'Sub_view' => number_format($stock_detail->Qty * $stock_detail->Cost, 2),
        'Sub' => $stock_detail->Qty * $stock_detail->Cost,
        'Movement_ID' => $stock_detail->Movement_ID,
        'check_edit' => \Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'E'),
        'check_delete' => \Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'D'),
        'route' => route('stocks.delete_detail', [ $stock , $stock_detail , 'corpID' => $request->corpID] )
      ]);
    }
    else
    {
      return response()->json([
        'status' => false,
      ]);
    }

  }

  public function update_detail(Request $request)
  {
    if(\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'E')) {
      $company = Corporation::findOrFail($request->corpID);
      $stockModel = new \App\Stock;
      $stockModel->setConnection($company->database_name);
      $stockDetailModel = new \App\StockDetail;
      $stockDetailModel->setConnection($company->database_name);
      
      $stock_detail = $stockDetailModel->find($request->Movement_ID);
      $have_update = false;
      if($request->id != $request->old_id)
      {
        $have_update = true;
        $stock_detail->item_id = intval($request->id);
      }
      $stock_detail->Qty = floatval($request->Qty);
      $stock_detail->Bal = floatval($request->Qty);
      $stock_detail->Cost = floatval($request->Cost);
        
      $success = $stock_detail->save();
      
      if($success)
      {
        return response()->json([
          'status' => $have_update,
          'item_id' => $stock_detail->item_id,
          "ItemCode" => $stock_detail->stock_item->ItemCode,
          "Brand" => $stock_detail->stock_item->brand->Brand,
          "Prod_Line" => $stock_detail->stock_item->product_line->Product,
        ]);
      }
    }
  }

  public function index(Request $request)
  {
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'V')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }
    $company = Corporation::findOrFail($request->corpID);
    $stockModel = new \App\Stock;
    $stockModel->setConnection($company->database_name);

    $one_vendor = false;
    $vendor_ID = "";
    if ($request->order == 'asc')
    {
      $next_order = 'desc';
    }
    else
    {
      $next_order = 'asc';
    }

    if( $request->vendor == "one" && $request->vendorID && ($request->vendorID != "")) {
      if($request->sortBy && $request->order)
      {
        $stocks = $stockModel->where('Supp_ID', $request->vendorID)->orderBy($request->sortBy,$request->order)->paginate(100);
        $one_vendor = true;
        $vendor_ID = $request->vendorID;
      }
      else
      {
        $stocks = $stockModel->where('Supp_ID', $request->vendorID)->orderBy('RcvDate','desc')->paginate(100);
        $one_vendor = true;
        $vendor_ID = $request->vendorID;
      }
    }
    else
    {
      if( $request->sortBy && $request->order)
      {
        $stocks = $stockModel->orderBy($request->sortBy,$request->order)->paginate(100);
      }
      else
      {
        $stocks = $stockModel->orderBy('RcvDate','desc')->paginate(100);
      }
    }
    
    $vendors = Vendor::orderBy('VendorName')->get();
    setcookie('last_index_url' , route('stocks.index', [
      'corpID' => $request->corpID,
      'vendor' => $request->vendor,
      'vendorID' => $vendor_ID,
      'sortBy' => $request->sortBy,
      'order' => $request->order
    ]));
    return view('stocks.index',
      [
        'corpID' => $request->corpID,
        'stocks' => $stocks,
        'vendors' => $vendors,
        'one_vendor' => $one_vendor,
        'vendor_ID' => $vendor_ID,
        'vendor_list_type' => $request->vendor,
        'next_order' => $next_order,
        'sortBy' => $request->sortBy
      ]
    );
  }

  public function create(Request $request)
  {
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
    return view('stocks.create',
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
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'A')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }
    $request->RcvDate = new Datetime($request->RcvDate);
    $request->RcvDate->setTime( date('H'), date('i'));
    $company = Corporation::findOrFail($request->corpID);
    $stockModel = new \App\Stock;
    $stockModel->setConnection($company->database_name);
    
    $stock = $stockModel;
    $stock->RR_No = $request->RR_No;
    $stock->RcvDate = $request->RcvDate;
    $stock->TotalAmt = floatval($request->total_amt);
    $stock->Supp_ID = $request->Supp_ID;
    $stock->RcvdBy = \Auth::user()->UserID;
    $stock->DateSaved = date('Y-m-d H:i:s');
    $stock->TotalAmt = $request->total_amt;
    $success = $stock->save();

    if ($success && is_array($request->stocks)) {
      foreach($request->stocks as $detail) {
        $stock_detail = new \App\StockDetail;
        $stock_detail->setConnection($company->database_name);

        $stock_detail->item_id = $detail['item_id'];
        $stock_detail->Qty = floatval($detail['qty']) ;
        $stock_detail->Bal = floatval($detail['qty']);
        $stock_detail->Cost = floatval($detail['cost']);
        $stock_detail->RcvDate = $request->RcvDate;
        $stock_detail->RR_No = $stock->RR_No;
        $stock_detail->save();

        $stock_item = StockItem::find($detail['item_id']);
        $stock_item->LastCost = $detail['cost'];
        $stock_item->save();

      }
    }

    \Session::flash('success', "D.R #$stock->RR_No is successfully created");
    return redirect()->route('stocks.show', [$stock, 'corpID' => $request->corpID, 'print' => $request->print ]);
  }

  public function destroy_detail(Request $request)
  {
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'D')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }
    $company = Corporation::findOrFail($request->corpID);
    $stockModel = new \App\Stock;
    $stockModel->setConnection($company->database_name);
    $stockDetailModel = new \App\StockDetail;
    $stockDetailModel->setConnection($company->database_name);

    $stock = $stockModel->find($request->stock_id);
    $detail = $stockDetailModel->find($request->detail_id);
    $detail->delete();
    return redirect()->route('stocks.show', [$stock, 'corpID' => $request->corpID ]);
  }

  public function get_details(Request $request)
  {
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'A')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }

    $company = Corporation::findOrFail($request->corpID);
    $purchaseOrderModel = new \App\PurchaseOrder;
    $purchaseOrderModel->setConnection($company->database_name);
    $details = $purchaseOrderModel->find( $request->po )->purchase_order_details;

    $result = [];
    foreach($details as $key => $value)
    {
      $item = StockItem::find($value->item_id);
      
      $array = [];
      $array['Brand'] = $item->brand->Brand;
      $array['Prod_Line'] = $item->product_line->Product;
      $array ['Unit']  = $item->Unit;
      $array['Description']  = $item->Description;
      $array['item_id']  = $value->item_id;
      $array['ItemCode']  = $value->ItemCode;
      $array['Qty']  = $value->Qty;
      $array['ServedQty']  = $value->ServedQty;
      $array['Cost'] = $value->cost;
      
      array_push($result, $array);
    }
    return response()->json([
      'details'=> $result
    ]);
  }

  public function destroy(Request $request)
  {
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'D')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }
    $company = Corporation::findOrFail($request->corpID);
    $stockModel = new \App\Stock;
    $stockModel->setConnection($company->database_name);

    $stock = $stockModel->find($request->stock);
    $dr = $stock->RR_No;
    $stockDetailModel = new \App\StockDetail;
    $stockDetailModel->setConnection($company->database_name);
    $stockDetailModel->where('RR_No', $dr)->delete();

    $success = $stock->delete();
    if($success){
      \Session::flash('success', "D.R. # $dr has been deleted"); 
      return back();
    }
  }

  public function searchStock(Request $request)
  {
    $items = StockItem::orderBy('s_prodline.Product', 'ASC')
                        ->orderBy('s_invtry_hdr.ItemCode', 'ASC')
                        ->orderBy('s_invtry_hdr.item_id')
                        ->where('s_invtry_hdr.Active','=',1)
                        ->where('s_invtry_hdr.Type','=',0)
                        ->select('s_invtry_hdr.*')
                        ->leftJoin('s_prodline', 's_prodline.ProdLine_ID', '=', 's_invtry_hdr.Prod_Line')
                        ->leftJoin('s_brands', 's_brands.Brand_ID', '=', 's_invtry_hdr.Brand_ID');
    if ($request->product_line) {
      $items = $items->where('s_prodline.Product','like','%' . $request->product_line.'%');
    }

    if ($request->brand) {
      $items = $items->where('s_brands.Brand','like','%' . $request->brand.'%');
    }
    
    if ($request->item_code) {
      $items = $items->where('s_invtry_hdr.ItemCode','like','%'.$request->item_code.'%');
    }

    $items = $items->get();

    return view('stocks.search-stock',[
      'items' => $items
    ]);
  }

  public function searchPO(Request $request)
  {
    $items = Spodetail::select('s_po_detail.*')
                      ->where('s_po_detail.po_no', '=', $request['po'])
                      ->groupBy('item_id')->get();

    return view('stocks.search-PO',[
      'items' => $items
    ]);
  }
}
