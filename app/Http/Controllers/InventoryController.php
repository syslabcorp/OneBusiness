<?php

namespace App\Http\Controllers;

use App\InventoryChange;
use Illuminate\Http\Request;
use Auth;
use App\Inventory;
use App\InventoryType;
use App\InventoryBrand;
use App\ProductLine;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!\Auth::user()->checkAccessById(19, "V"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //user access rights
        $articles = DB::table('s_invtry_hdr')
            ->join('s_prodline', 's_invtry_hdr.Prod_Line', '=', 's_prodline.ProdLine_ID')
            ->join('s_brands', 's_invtry_hdr.Brand_ID', '=', 's_brands.Brand_ID')
            ->join('s_invtry_type', 's_invtry_hdr.Type', '=', 's_invtry_type.inv_type')
            ->select('s_invtry_hdr.*','s_invtry_hdr.Active as Active', 's_prodline.Product as Product', 's_brands.Brand as Brand',
                's_invtry_type.type_desc')
            ->orderBy('s_invtry_hdr.item_id', 'DESC')
            ->get();

        return view('inventory.index', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!\Auth::user()->checkAccessById(19, "A"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //get instances of inventoryType
        $invTypes = InventoryType::orderBy('type_desc', 'ASC')->get();

        //get brand instances
        $brands = InventoryBrand::orderBy('Brand', 'ASC')->get();

        //get products instances
        $products = ProductLine::orderBy('Product', 'ASC')->get();

        return view('inventory.create')
            ->with('invTypes', $invTypes)
            ->with('brands', $brands)
            ->with('products', $products);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(!\Auth::user()->checkAccessById(19, "A"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //validate request
        $this->validate($request, [
            'itemCode' => 'required',
            'itemType' => 'required',
            'itemBrand' => 'required',
            'itemProduct' => 'required',
            'itemUnit' => 'required',
            'itemPackageQuantity' => 'required',
            'itemThresholdQty' => 'required',
            'itemMultipDays' => 'required',
            'itemMinLevel' => 'required',
        ]);

        //if validation passes add item in the inventory
        $inventory = new Inventory();
        $inventory->ItemCode = $request->itemCode;
        $inventory->Brand_ID = $request->itemBrand;
        $inventory->barcode = $request->barcodeNum;
        $inventory->Prod_Line = $request->itemProduct;
        $inventory->Description = $request->itemDescription;
        $inventory->Unit = $request->itemUnit;
        $inventory->Packaging = $request->itemPackageQuantity;
        $inventory->Threshold = $request->itemThresholdQty;
        $inventory->Multiplier = $request->itemMultipDays;
        $inventory->Type = $request->itemType;
        $inventory->Min_Level = $request->itemMinLevel;
        $inventory->TrackThis = ($request->itemTrackIventory) ? 1 : 0;
        $inventory->Print_This = ($request->itemPrintStub) ? 1 : 0;
        $inventory->Active = ($request->itemActive) ? 1 : 0;
        $inventory->save();

        \Session::flash('alert-class', "Item added successfully");
        return redirect()->route('inventory.index');

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
        if(!\Auth::user()->checkAccessById(19, "E")) {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //find instance
        $inventory = Inventory::where('item_id', $id)->first();

        //get instances of inventoryType
        $invTypes = InventoryType::orderBy('type_desc', 'ASC')->get();

        //get brand instances
        $brands = InventoryBrand::orderBy('Brand', 'ASC')->get();

        //get products instances
        $products = ProductLine::orderBy('Product', 'ASC')->get();

        return view('inventory.edit')
            ->with('inventory', $inventory)
            ->with('invTypes', $invTypes)
            ->with('brands', $brands)
            ->with('products', $products);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!\Auth::user()->checkAccessById(19, "E")) {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //validate request
        $this->validate($request, [
            'itemCode' => 'required',
            'itemType' => 'required',
            'itemBrand' => 'required',
            'itemProduct' => 'required',
            'itemUnit' => 'required',
            'itemPackageQuantity' => 'required',
            'itemThresholdQty' => 'required',
            'itemMultipDays' => 'required',
            'itemMinLevel' => 'required',
        ]);

        //if validation passes add item in the inventory
        $inventory = Inventory::where('item_id', $id)->first();
        $inventory->ItemCode = $request->itemCode;
        $inventory->Brand_ID = $request->itemBrand;
        $inventory->barcode = $request->barcodeNum;
        $inventory->Prod_Line = $request->itemProduct;
        $inventory->Description = $request->itemDescription;
        $inventory->Unit = $request->itemUnit;
        $inventory->Packaging = $request->itemPackageQuantity;
        $inventory->Threshold = $request->itemThresholdQty;
        $inventory->Multiplier = $request->itemMultipDays;
        $inventory->Type = $request->itemType;
        $inventory->Min_Level = $request->itemMinLevel;
        $inventory->TrackThis = ($request->itemTrackIventory) ? 1 : 0;
        $inventory->Print_This = ($request->itemPrintStub) ? 1 : 0;
        $inventory->Active = ($request->itemActive) ? 1 : 0;
        $inventory->save();


        //check if item exists in the s_changes table
        $inventoryItem = InventoryChange::where('invtry_hdr', $inventory->id)->first();
        if($inventoryItem){
            $inventoryItem->invtry_hdr = 1;
           $success = $inventoryItem->save();

        }else{
            $inventoryChange = new InventoryChange;
            $inventoryChange->invtry_hdr = 1;
            $success = $inventoryChange->save();

        }

        if($success){
            \Session::flash('alert-class', "Item updated successfully");
            return redirect()->route('inventory.index');
        }
        \Session::flash('flash_message', "Something went wrong!");
        return back()->withInput();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!\Auth::user()->checkAccessById(19, "D"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        $inventoryItem = Inventory::where("item_id", $id)->delete();
        if($inventoryItem) {
            \Session::flash('alert-class', "Item deleted successfully");
            return redirect()->route('inventory.index');
        }
    }
}
