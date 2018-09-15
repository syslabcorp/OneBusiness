<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Company;
use Illuminate\Http\Request;
use App\Models\Corporation;
use \App\Models\Equip\Brands;
class BrandsController extends Controller
{
    public function index()
    {
        $brands = Brands::all();
        return view('assets.brands.index', [
            'brands' => $brands
        ]);
    }
    public function store(Request $request)
    {
        Brands::create($request->only('description'));
        \Session::flash('success', 'Brand has been created successfully');

        return redirect()->back();
    }
    public function destroy($id)
    {
        $brand = Brands::findOrFail($id);
        $brand->delete();
        return response()->json(['success' => true]);
    }
    public function update(Request $request, $id)
    {
        $brand = Brands::findOrFail($id);
        $brand->update(
            $request->only([
                'description'
            ])
        );

        \Session::flash('success', 'Brand has been updated successfully');

        return redirect()->back();
    }

}
