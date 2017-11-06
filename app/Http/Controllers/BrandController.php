<?php

namespace App\Http\Controllers;

use App\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!\Auth::user()->checkAccessById(23, "V"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }
        //retrieve brands
        $brands = Brand::orderBy('Brand_ID', 'ASC')->get();

        return view('brands.index')
            ->with('brands', $brands);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!\Auth::user()->checkAccessById(23, "A"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //validate request
        $this->validate($request, [
            'brandName' => 'required'
        ]);

        //if validator passed store service item
        $brand = new Brand;
        $brand->Brand = $request->input('brandName');
        $brand->save();

        \Session::flash('alert-class', "Brand added successfully");
        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        if(!\Auth::user()->checkAccessById(23, "E")) {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //if validator passed store service item
        $brand = Brand::where('Brand_ID', $brand->Brand_ID)->first();
        $brand->Brand = $request->input('editBrandName');
        $brand->save();

        //updates s_changes table
        DB::table('s_changes')->update(['brands' => 1]);

        \Session::flash('alert-class', "Brand updated successfully");
        return redirect()->route('brands.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        if(!\Auth::user()->checkAccessById(23, "D"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }


        $success = $brand->delete();
        if($success){
            \Session::flash('alert-class', "Brand deleted successfully");
            return redirect()->route('brands.index');
        }
    }
}
