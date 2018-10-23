<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Equip\Brands;
use App\Models\Equip\Category;
use App\Models\Vendor;
use App\Models\Item\Master;

class PartsController extends Controller
{
    public function index(){
        if(!\Auth::user()->checkAccessById(57, "V")) {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        $brands = Brands::orderBy('description')->get();
        $categories = Category::orderBy('description')->get();
        $vendors = Vendor::orderBy('VendorName')->get();

        return view('parts.index', [
            'brands' => $brands,
            'categories' => $categories,
            'vendors' => $vendors
        ]);
    }

    public function store(Request $request) {
        if(!\Auth::user()->checkAccessById(57, "A")) {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        $item = Master::create($request->only([
            'description', 'brand_id', 'cat_id', 'supplier_id', 'consumable', 'with_serialno', 'isActive'
        ]));

        \Session::flash('success', "Part #" . $item->item_id . ' has been created.');

        return redirect(route('parts.index'));
    }

    public function edit($id){
        if(!\Auth::user()->checkAccessById(57, "E")) {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }
        
        $item = Master::findOrFail($id);
        
        $brands = Brands::orderBy('description')->get();
        $categories = Category::orderBy('description')->get();
        $vendors = Vendor::orderBy('VendorName')->get();

        return view('parts.modal-edit', [
            'item' => $item,
            'brands' => $brands,
            'categories' => $categories,
            'vendors' => $vendors
        ]);
    }

    public function update($item, Request $request){
        if(!\Auth::user()->checkAccessById(57, "E")) {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        $item = Master::findOrFail($item);

        $item->update($request->only([
            'description', 'brand_id', 'cat_id', 'supplier_id', 'consumable', 'with_serialno', 'isActive'
        ]));

        \Session::flash('success', "Part #" . $item->item_id . ' has been updated.');

        return redirect(route('parts.index'));
    }

    public function destroy($item){
        if(!\Auth::user()->checkAccessById(57, "D")) {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        $item = Master::destroy($item);

        return response()->json([
            'success' => true
        ]);
    }

    public function searchPart(Request $request){
        $items = Master::orderBy('item_master.description')
                        ->select('item_master.*');
        
        if ($request->description){
            $items = $items->where('item_master.description','like','%' . $request->description .'%');
        }
        
        if ($request->brand){
            $items = $items->leftJoin('equip_brands', 'equip_brands.brand_id', '=', 'item_master.brand_id')
                            ->where('equip_brands.description','like','%' . $request->brand .'%');
        }

        if ($request->category){
            $items = $items->leftJoin('equip_category', 'equip_category.cat_id', '=', 'item_master.cat_id')
                            ->where('equip_category.description','like','%' . $request->category .'%');
        }

        if ($request->vendor){
            $items = $items->leftJoin('s_vendors', 's_vendors.Supp_ID', '=', 'item_master.supplier_id')
                            ->where('s_vendors.VendorName','like','%' . $request->vendor .'%');
        }
        
        $items = $items->get();
  
        return view('parts.search-part', [
            'items' => $items
        ]);
    }

    public function getFilter(Request $request){
        $items = [];

        if($request->type == 'brand'){
            $brands = Brands::orderBy('description')->get();
            $items = $brands->map(function($brand) {
                return [
                    'id' => $brand->brand_id,
                    'label' => $brand->description
                ];
            });
        }
        elseif($request->type == 'category'){
            $categories = Category::orderBy('description')->get();
            $items = $categories->map(function($category) {
                return [
                    'id' => $category->cat_id,
                    'label' => $category->description
                ];
            });
        }
        else{
            $vendors = Vendor::orderBy('VendorName')->get();
            $items = $vendors->map(function($vendor){
                return [
                    'id' => $vendor->Supp_ID,
                    'label' => $vendor->VendorName
                ];
            });
        }
        
        return response()->json([
            'type' => $request->type,
            'items' => $items
        ]);
        
    }
}
