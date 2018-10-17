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
        $brands = Brands::orderBy('description')->get();
        $categories = Category::orderBy('description')->get();
        $vendors = Vendor::orderBy('VendorName')->get();

        return view('parts.index', [
            'brands' => $brands,
            'categories' => $categories,
            'vendors' => $vendors
        ]);
    }

    public function store(Request $request){
        $item = Master::create($request->only([
            'description', 'brand_id', 'cat_id', 'supplier_id', 'consumable', 'with_serialno', 'isActive'
        ]));

        \Session::flash('success', "Part #" . $item->item_id . ' has been created.');

        return redirect(route('parts.index'));
    }

    public function edit($id){
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
        $item = Master::findOrFail($item);

        $item->update($request->only([
            'description', 'brand_id', 'cat_id', 'supplier_id', 'consumable', 'with_serialno', 'isActive'
        ]));

        \Session::flash('success', "Part #" . $item->item_id . ' has been updated.');

        return redirect(route('parts.index'));
    }

    public function destroy($item){
        $item = Master::destroy($item);

        return response()->json([
            'success' => true
        ]);
    }

    public function searchPart(Request $request){
        $items = Master::orderBy('description');

        if ($request->description) {
            $items = $items->where('description','like','%' . $request->description .'%');
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
