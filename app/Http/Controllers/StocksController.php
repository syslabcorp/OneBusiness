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

class StocksController extends Controller
{
  public function show(Request $request)
  {
    $stockitems = StockItem::where( 'Active', 1 )->get();
    $stock = Stock::find($request->stock);
    $stock_details = $stock->stock_details;
    $vendors = Vendor::all();
    $pos = PurchaseOrder::where('served', 0)->get();
    return view('stocks.show',
      [
        'corpID' => $request->corpID,
        'vendors' => $vendors,
        'stock' => $stock,
        'stock_details' => $stock_details,
        'pos' => $pos,
        'stockitems' => $stockitems
      ]
    )->with('corpID', $request->corpID);
  }

  public function update(Request $request, Stock $stock)
  {
    // dd($request->all());
  //   $this->validate($request,[
  //     'ItemCode' => 'required|max:15',
  //     'ServedQty' => 'required|numeric',
  //     'Qty' => 'required',
  //     'Bal' => 'required',
  //     'RR_No' => 'required',
  //     'RcvDate' => 'required',
  //     'Cost' => 'required'
  // ]);

    if($request->item_id){
      $stock_detail = new StockDetail;
      $stock_detail->item_id = intval($request->item_id);
      $stock_detail->ItemCode = $request->ItemCode;
      $stock_detail->ServedQty = intval($request->ServedQty);
      $stock_detail->Qty = floatval($request->Qty);
      $stock_detail->Bal = floatval($request->Qty);
      $stock_detail->RR_No = $request->RR_No;
      $stock_detail->RcvDate = $request->RcvDate;
      $stock_detail->Cost = $request->Cost;
      $success = $stock_detail->save();
      // dd($stock_detail);

      if($success && $request->po && ($request->po != ""))
      {
        
        $detail = new PurchaseOrderDetail;
        $detail->item_id = intval($request->item_id);
        $detail->ItemCode = $request->ItemCode;
        $detail->po_no = $request->po;
        $detail->Qty = floatval($request->Qty);
        $detail->ServedQty = intval($request->ServedQty);
        $detail->cost = $request->Cost;
        $detail->save();
      }
    }

    if($request->ItemCode_Update)
    {
      foreach($request->ItemCode_Update as $key => $value)
      {
        $detail = StockDetail::find($key);
        $detail->ItemCode = $value;
        $detail->save();
      }
    }
    // dd( Redirect::action('StocksController@show', [$stock, 'corpID' => $request->corpID ]));
    // return Redirect::action('StocksController@show', [$stock, 'corpID' => $request->corpID ]);
    return redirect()->route('stocks.show', [$stock, 'corpID' => $request->corpID ]);
  }

  public function index(Request $request)
  {
    $one_vendor = false;
    $vendor_ID = "";

    if( $request->vendor == "one" && $request->vendorID && ($request->vendorID != ""))
    {
      $stocks = Stock::where('Supp_ID', $request->vendorID)->get();
      $one_vendor = true;
      $vendor_ID = $request->vendorID;
    }
    else
    {
      $stocks = Stock::all();
    }
    
    $vendors = Vendor::all();
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
    $vendors = Vendor::all();
    $stockitems = StockItem::where( 'Active', 1 )->get();
    $pos = PurchaseOrder::where('served', 0)->get();
    return view('stocks.create',
      [
        'corpID' => $request->corpID,
        'vendors' => $vendors,
        'pos' => $pos,
        'stockitems' => $stockitems
      ]
    );
  }

  public function destroy_detail(Request $request)
  {
    $stock = Stock::find($request->stock_id);
    $detail = StockDetail::find($request->detail_id);
    // dd($request->stock_id);
    $detail->delete();
    return redirect()->route('stocks.show', [$stock, 'corpID' => $request->corpID ]);
  }

  public function destroy(Request $request,Stock $stock)
  {
    // dd($request);
    $success = $stock->delete();
    if($success){
      // \Session::flash('success', "Brand deleted successfully");
      return redirect()->route('stocks.index', ['corpID' => $request->corpID]);
    }
  }
}
