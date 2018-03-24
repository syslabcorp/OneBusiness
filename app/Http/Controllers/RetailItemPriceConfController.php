<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Corporation;
use App\ProductLine;

class RetailItemPriceConfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!\Auth::user()->checkAccessById(36, "V")) {
          \Session::flash('error', "You don't have permission");
          return redirect("/home");
        }

        //get services list
        $corporations = Corporation::orderBy('corp_name', 'ASC')->get(['corp_id', 'corp_name', 'database_name']);
        $products = ProductLine::orderBy('Product', 'ASC')->get(['ProdLine_ID', 'Product', 'Active']);

        return view('retail-items-price-conf.index', compact(['corporations', 'products']));
    }

    public function create(Request $request) {
      if(!\Auth::user()->checkAccessById(36, "V")) {
        \Session::flash('error', "You don't have permission");
        return redirect("/home");
      }

      $company = Corporation::findOrFail($request->corpID);
      $itemModel = new \App\SItemCfg;

      $stocks = \App\StockItem::whereIn('item_id', explode(',', $request->item_ids))->get();
      $branches = \App\Branch::whereIn('Branch', explode(',', $request->branch_ids))->get();

      return view('retail-items-price-conf.new', [
        'branches' => $branches,
        'stocks' => $stocks,
        'itemModel' => $itemModel,
        'corpID' => $request->corpID,
        'branch_ids' => $request->branch_ids,
        'item_ids' => $request->item_ids
      ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
      $company = Corporation::findOrFail($request->corpID);
      $itemModel = new \App\SItemCfg;

      foreach($request->items as $item_id => $items) {
        foreach($items as $Branch => $item) {
          $status = $itemModel->updateOrCreate([
            'item_id' => $item_id,
            'Branch' => $Branch
          ], $item);
        }
      }

      \Session::flash('success', "Settings successfully saved");

      return redirect(route('retail-items-price-conf.create', [
        'corpID' => $request->corpID,
        'branch_ids' => $request->branch_ids,
        'item_ids' => $request->item_ids
      ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id) {
      $branches = \App\Branch::whereIn('Branch', $request->branch_ids)->get();
      $itemModel = new \App\SItemCfg;

      $items = $itemModel->where('Branch', '=', $request->branch_id)->get();

      foreach($branches as $branch) {
        if($branch->Branch == $request->branch_id) continue;

        foreach($items as $item) {
          $itemModel->updateOrCreate([
            'item_id' => $item->item_id,
            'Branch' => $branch->Branch
          ], [
            'ItemCode' => $item->ItemCode,
            'Sell_Price' => $item->Sell_Price,
            'Min_Level' => $item->Min_Level,
            'Active' => $item->Active,
            'pts_price' => $item->pts_price,
            'pts_redeemable' => $item->pts_redeemable
          ]);
        }
      }

      \Session::flash('success', "Retail items are successfully copied");

      return redirect(route('retail-items-price-conf.index', [
      ])); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
