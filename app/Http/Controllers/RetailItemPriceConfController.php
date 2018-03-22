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
        if(!\Auth::user()->checkAccessById(34, "V"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //get services list
        $corporations = Corporation::orderBy('corp_name', 'ASC')->get(['corp_id', 'corp_name']);
        $products = ProductLine::orderBy('Product', 'ASC')->get(['ProdLine_ID', 'Product', 'Active']);

        return view('retail-items-price-conf.index', compact(['corporations', 'products']));
    }

    public function create(Request $request) {
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

      \Session::flash('success', "Retail items are successfully updated");

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
