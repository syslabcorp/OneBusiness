<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Stock;
use App\StockItem;
use App\StockDetail;
use App\Vendor;
use App\PurchaseOrder;
use App\PurchaseOrderDetail;
use App\Corporation;
use App\Brand;
use App\ProductLine;
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

    $stockitems = StockItem::where( 'Active', 1 )->where('Type', 0)->orderBy('ItemCode')->get();
    $stock = $stockModel->find($request->stock);
    $stock_details = $stock->stock_details;
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
    $pos = $purchaseOrderModel->where('served', 0)->get();
    return view('stocks.show',
      [
        'brands' => $brands,
        'prod_lines' => $prod_lines,
        'corpID' => $request->corpID,
        'vendors' => $vendors,
        'stock' => $stock,
        'stock_details' => $stock_details,
        'pos' => $pos,
        'stockitems' => $stockitems
      ]
    )->with('corpID', $request->corpID);
  }

  public function update(Request $request)
  {
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'A')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }    
    $request->RcvDate = new Datetime($request->RcvDate);
    $company = Corporation::findOrFail($request->corpID);
    $stockModel = new \App\Stock;
    $stockModel->setConnection($company->database_name);
    $stockDetailModel = new \App\StockDetail;
    $stockDetailModel->setConnection($company->database_name);
    $purchaseOrderDetailModel = new \App\PurchaseOrderDetail;
    $purchaseOrderDetailModel->setConnection($company->database_name);

    $stock = $stockModel->find($request->stock); 
    $stock->Supp_ID = $request->Supp_ID;
    $stock->RcvDate = $request->RcvDate;
    
    $stock->save();
    if($request->type)
    {
      foreach ($request->type as $key => $value)
      {
        $stock_detail = $stockDetailModel->find($key);
        
        //edit row
        if($value == "none")
        {
          if($request->old_item_id[$key] != $request->item_id[$key])
          {
            $stock_detail->item_id = intval($request->item_id[$key]);
          }
          //$stock_detail->ItemCode = $request->ItemCode[$key];
          $stock_detail->Qty = floatval($request->Qty[$key]) ;
          $stock_detail->Bal = floatval($request->Qty[$key]);
          $stock_detail->Cost = floatval($request->Cost[$key]);
          $stock_detail->save();

          $stock_item = StockItem::find($stock_detail->item_id);
          $stock_item->LastCost = $stock_detail->Cost;
          $stock_item->save();
        }
  
        //delete row
        if($value == "deleted")
        {
          $stock_detail->delete();
        }
      }
    }
    if($request->add_type)
    {
      foreach ($request->add_type as $key => $value)
      {
        $stockDetailModel = new \App\StockDetail;
        $stockDetailModel->setConnection($company->database_name);

        $stock_detail = $stockDetailModel;
        
        //add row
        if($value == "add" && $request->add_item_id[$key] )
        {
          $stock_detail->item_id = intval($request->add_item_id[$key]);
          //$stock_detail->ItemCode = $request->add_ItemCode[$key];
          $stock_detail->Qty = floatval($request->add_Qty[$key]) ;
          $stock_detail->Bal = floatval($request->add_Qty[$key]);
          $stock_detail->Cost = floatval($request->add_Cost[$key]);
          $stock_detail->RcvDate = $request->RcvDate;
          $stock_detail->RR_No = $request->RR_No;
          
          $stock_detail->save();

          $stock_item = StockItem::find($stock_detail->item_id);
          $stock_item->LastCost = $stock_detail->Cost;
          $stock_item->save();

        }
      }
    }

    $stock->TotalAmt = floatval($request->total_amt);
    $stock->save();
    \Session::flash('success', "D.R #$stock->RR_No is successfully updated");
    return redirect()->route('stocks.show', [$stock, 'corpID' => $request->corpID ]);
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
        $detail->ItemCode = $request->ItemCode;
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

    if( $request->vendor == "one" && $request->vendorID && ($request->vendorID != "")) {
      $stocks = $stockModel->where('Supp_ID', $request->vendorID)->orderBy('txn_no','desc')->paginate(100);
      $one_vendor = true;
      $vendor_ID = $request->vendorID;
    }
    else{
      $stocks = $stockModel->orderBy('txn_no','desc')->paginate(100);
    }
    
    $vendors = Vendor::orderBy('VendorName')->get();
    setcookie('last_index_url' , route('stocks.index', [
      'corpID' => $request->corpID,
      'vendor' => $request->vendor,
      'vendorID' => $vendor_ID
    ]));
    return view('stocks.index',
      [
        'corpID' => $request->corpID,
        'stocks' => $stocks,
        'vendors' => $vendors,
        'one_vendor' => $one_vendor,
        'vendor_ID' => $vendor_ID
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

    $stockitems = StockItem::where( 'Active', 1 )->orderBy('ItemCode')->get();
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
    $company = Corporation::findOrFail($request->corpID);
    $stockModel = new \App\Stock;
    $stockModel->setConnection($company->database_name);
    
    $stock = $stockModel;
    $stock->RR_No = $request->RR_No;
    $stock->RcvDate = $request->RcvDate;
    $stock->TotalAmt = floatval($request->total_amt);
    $stock->Supp_ID = $request->Supp_ID;
    $success = $stock->save();

    if($success && $request->add_type)
    {
      foreach ($request->add_type as $key => $value)
      {
        $stockDetailModel = new \App\StockDetail;
        $stockDetailModel->setConnection($company->database_name);

        $stock_detail = $stockDetailModel;
        
        //add row
        if($value == "add" && $request->add_item_id[$key] )
        {
          $stock_detail->item_id = intval($request->add_item_id[$key]);
          //$stock_detail->ItemCode = $request->add_ItemCode[$key];
          $stock_detail->Qty = floatval($request->add_Qty[$key]) ;
          $stock_detail->Bal = floatval($request->add_Qty[$key]);
          $stock_detail->Cost = floatval($request->add_Cost[$key]);
          $stock_detail->RcvDate = $request->RcvDate;
          $stock_detail->RR_No = $stock->RR_No;
          $stock_detail->save();

          $stock_item = StockItem::find($stock_detail->item_id);
          $stock_item->LastCost = $stock_detail->Cost;
          $stock_item->save();
        }
      }

      // if($success && $request->po && ($request->po != ""))
      // {
      //   $purchaseOrderDetailModel = new \App\PurchaseOrderDetail;
      //   $purchaseOrderDetailModel->setConnection($company->database_name);
      //   $detail = $purchaseOrderDetailModel;
      //   $detail->item_id = intval($request->add_item_id[$key]);
      //   $detail->ItemCode = $request->add_ItemCode[$key];
      //   $detail->po_no = $request->po;
      //   $detail->Qty = floatval($request->add_Qty[$key]);
      //   $detail->cost = $request->add_Cost[$key];
      //   $detail->save();
      // }
    }
    \Session::flash('success', "D.R #$stock->RR_No is successfully created");
    return redirect()->route('stocks.show', [$stock, 'corpID' => $request->corpID ]);
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
}
