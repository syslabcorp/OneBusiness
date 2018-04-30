<?php

namespace App\Http\Controllers;

use Config;
use App\InventoryChange;
use Illuminate\Http\Request;
use Auth;
use App\Inventory;
use App\SItemCfg;
use App\InventoryType;
use App\InventoryBrand;
use App\ProductLine;
use App\Branch;
use App\Company;
use Illuminate\Support\Facades\DB;
use DB as VLDB;

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
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        return view('inventory.index');
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
            \Session::flash('error', "You don't have permission");
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
            \Session::flash('error', "You don't have permission");
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

        // foreach  (Config::get('database')['connections'] as $key => $value )
        // {
        //     if($value['driver'] == "mysql")
        //     {
        //         $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME =  ?";
        //         $query_check_exist_table = "SELECT * FROM information_schema.tables WHERE table_schema = ? AND table_name = ?";
        //         $db = VLDB::select($query, [$value['database']]);
        //         if(!empty($db))
        //         {
        //             $table = VLDB::select($query_check_exist_table, [$value['database'], 's_item_cfg' ]);
        //             if(!empty($table))
        //             {
        //                 $last_item_id = VLDB::connection($key)->table('s_item_cfg')->get()->last()->item_id;
        //                 $list_item = VLDB::connection($key)->table('s_item_cfg')->where('item_id' , $last_item_id)->get();
                        
        //                 foreach( $list_item as $item )
        //                 {
        //                     $new_item = new \App\SItemCfg;
        //                     $new_item->setConnection($key);
        //                     $new_item->item_id = $inventory->item_id;
        //                     $new_item->Branch = $item->Branch;
        //                     $new_item->ItemCode = $inventory->ItemCode;
        //                     $new_item->save();
        //                 } 
        //             }
        //         }
        //     }
        // }
        
        $branches = Branch::all();
        foreach( $branches as $branch )
        {
            $company = Company::find($branch->corp_id);
            if($company && $company->database_name)
            {
                $new_item = new \App\SItemCfg;
                $new_item->setConnection($company->database_name);
                $new_item->item_id = $inventory->item_id;
                $new_item->Branch = $branch->Branch;
                $new_item->ItemCode = $inventory->ItemCode;
                $new_item->save();
            }
        }

        \Session::flash('success', "Item added successfully");
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
            \Session::flash('error', "You don't have permission");
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
            \Session::flash('error', "You don't have permission");
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
        $success = $inventory->save();


        //update inventory table
        DB::table('s_changes')->update(['invtry_hdr' => 1]);

        if($success){
            \Session::flash('success', "Item updated successfully");
            return redirect()->route('inventory.index');
        }
        \Session::flash('error', "Something went wrong!");
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
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        $inventoryItem = Inventory::where("item_id", $id)->delete();
        if($inventoryItem) {
            \Session::flash('success', "Item deleted successfully");
            return redirect()->route('inventory.index');
        }
    }

    public function getInventoryList(Request $request){

        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $columns = $request->input('columns');
        $orderable = $request->input('order');
        $orderNumColumn = $orderable[0]['column'];
        $orderDirection = $orderable[0]['dir'];
        $columnName = $columns[$orderNumColumn]['data'];
        $search = $request->input('search');

        $articlesCount = DB::table('s_invtry_hdr')
            ->join('s_prodline', 's_invtry_hdr.Prod_Line', '=', 's_prodline.ProdLine_ID')
            ->join('s_brands', 's_invtry_hdr.Brand_ID', '=', 's_brands.Brand_ID')
            ->join('s_invtry_type', 's_invtry_hdr.Type', '=', 's_invtry_type.inv_type')
            ->count();

        if($search['value'] == ""){
            //user access rights
            if($columnName == "Product")
            {
                $articles = DB::table('s_invtry_hdr')
                ->join('s_prodline', 's_invtry_hdr.Prod_Line', '=', 's_prodline.ProdLine_ID')
                ->join('s_brands', 's_invtry_hdr.Brand_ID', '=', 's_brands.Brand_ID')
                ->join('s_invtry_type', 's_invtry_hdr.Type', '=', 's_invtry_type.inv_type')
                ->select('s_invtry_hdr.*','s_invtry_hdr.Active as Active', 's_prodline.Product as Product', 's_brands.Brand as Brand',
                    's_invtry_type.type_desc')
                ->orderBy('s_prodline.'.$columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();
            }
            else if ( $columnName == "Brand")
            {
                $articles = DB::table('s_invtry_hdr')
                ->join('s_prodline', 's_invtry_hdr.Prod_Line', '=', 's_prodline.ProdLine_ID')
                ->join('s_brands', 's_invtry_hdr.Brand_ID', '=', 's_brands.Brand_ID')
                ->join('s_invtry_type', 's_invtry_hdr.Type', '=', 's_invtry_type.inv_type')
                ->select('s_invtry_hdr.*','s_invtry_hdr.Active as Active', 's_prodline.Product as Product', 's_brands.Brand as Brand',
                    's_invtry_type.type_desc')
                ->orderBy('s_brands.'.$columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();
            }
            else
            {
                $articles = DB::table('s_invtry_hdr')
                ->join('s_prodline', 's_invtry_hdr.Prod_Line', '=', 's_prodline.ProdLine_ID')
                ->join('s_brands', 's_invtry_hdr.Brand_ID', '=', 's_brands.Brand_ID')
                ->join('s_invtry_type', 's_invtry_hdr.Type', '=', 's_invtry_type.inv_type')
                ->select('s_invtry_hdr.*','s_invtry_hdr.Active as Active', 's_prodline.Product as Product', 's_brands.Brand as Brand',
                    's_invtry_type.type_desc')
                ->orderBy('s_invtry_hdr.'.$columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();
            }


            $pagination = DB::table('s_invtry_hdr')
                ->join('s_prodline', 's_invtry_hdr.Prod_Line', '=', 's_prodline.ProdLine_ID')
                ->join('s_brands', 's_invtry_hdr.Brand_ID', '=', 's_brands.Brand_ID')
                ->join('s_invtry_type', 's_invtry_hdr.Type', '=', 's_invtry_type.inv_type')
                ->count();
        }else if($search['value'] != ""){
            //user access rights
            if($columnName == "Product")
            {
                $articles = DB::table('s_invtry_hdr')
                ->join('s_prodline', 's_invtry_hdr.Prod_Line', '=', 's_prodline.ProdLine_ID')
                ->join('s_brands', 's_invtry_hdr.Brand_ID', '=', 's_brands.Brand_ID')
                ->join('s_invtry_type', 's_invtry_hdr.Type', '=', 's_invtry_type.inv_type')
                ->where(function ($q) use ($search, $columns){
                    for($i = 0; $i<12; $i++){
                        $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                    }
                })
                ->select('s_invtry_hdr.*','s_invtry_hdr.Active as Active', 's_prodline.Product as Product', 's_brands.Brand as Brand',
                    's_invtry_type.type_desc')
                ->orderBy('s_prodline.'.$columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();
            }
            else if($columnName == "Brand")
            {
                $articles = DB::table('s_invtry_hdr')
                ->join('s_prodline', 's_invtry_hdr.Prod_Line', '=', 's_prodline.ProdLine_ID')
                ->join('s_brands', 's_invtry_hdr.Brand_ID', '=', 's_brands.Brand_ID')
                ->join('s_invtry_type', 's_invtry_hdr.Type', '=', 's_invtry_type.inv_type')
                ->where(function ($q) use ($search, $columns){
                    for($i = 0; $i<12; $i++){
                        $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                    }
                })
                ->select('s_invtry_hdr.*','s_invtry_hdr.Active as Active', 's_prodline.Product as Product', 's_brands.Brand as Brand',
                    's_invtry_type.type_desc')
                ->orderBy('s_brands.'.$columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();
            }
            else
            {
                $articles = DB::table('s_invtry_hdr')
                ->join('s_prodline', 's_invtry_hdr.Prod_Line', '=', 's_prodline.ProdLine_ID')
                ->join('s_brands', 's_invtry_hdr.Brand_ID', '=', 's_brands.Brand_ID')
                ->join('s_invtry_type', 's_invtry_hdr.Type', '=', 's_invtry_type.inv_type')
                ->where(function ($q) use ($search, $columns){
                    for($i = 0; $i<12; $i++){
                        $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                    }
                })
                ->select('s_invtry_hdr.*','s_invtry_hdr.Active as Active', 's_prodline.Product as Product', 's_brands.Brand as Brand',
                    's_invtry_type.type_desc')
                ->orderBy('s_invtry_hdr.'.$columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();
            }


            $pagination = DB::table('s_invtry_hdr')
                ->join('s_prodline', 's_invtry_hdr.Prod_Line', '=', 's_prodline.ProdLine_ID')
                ->join('s_brands', 's_invtry_hdr.Brand_ID', '=', 's_brands.Brand_ID')
                ->join('s_invtry_type', 's_invtry_hdr.Type', '=', 's_invtry_type.inv_type')
                ->where(function ($q) use ($search, $columns){
                    for($i = 0; $i<12; $i++){
                        $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                    }
                })
                ->count();
        }


        $columns = array(
            "draw" => $draw,
            "recordsTotal" => $articlesCount,
            "recordsFiltered" =>  isset($pagination) && ($pagination != "") ? $pagination : 0,
            "data" => ($articles != null) ? $articles : 0
        );

        return response()->json($columns, 200);
    }
}
