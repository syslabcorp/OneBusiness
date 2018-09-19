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
        if(!\Auth::user()->checkAccessById(54, 'V')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $brands = Brands::all();
        return view('assets.brands.index', [
            'brands' => $brands
        ]);
    }
    public function store(Request $request)
    {
        if(!\Auth::user()->checkAccessById(54, 'A')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        Brands::create($request->only('description'));
        \Session::flash('success', 'Brand has been created successfully');

        return redirect()->back();
    }
    public function destroy($id)
    {
        if(!\Auth::user()->checkAccessById(54, 'D')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $brand = Brands::findOrFail($id);
        $brand->delete();
        return response()->json(['success' => true]);
    }
    public function update(Request $request, $id)
    {
        if(!\Auth::user()->checkAccessById(54, 'E')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

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
