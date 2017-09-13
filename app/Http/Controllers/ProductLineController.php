<?php

namespace App\Http\Controllers;

use App\ProductLine;
use Illuminate\Http\Request;

class ProductLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!\Auth::user()->checkAccess("24", "V"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //retrieve product line instances
        $products = ProductLine::orderBy('ProdLine_ID', 'ASC')->get();

        return view('productsLine.index')
            ->with('products', $products);
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
        if(!\Auth::user()->checkAccess("24", "A"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //validate request
        $this->validate($request, [
            'prodcutLineName' => 'required'
        ]);

        //if validator passed store service item
        $product = new ProductLine;
        $product->Product = $request->input('prodcutLineName');
        $product->Active = 1;
        $product->save();

        \Session::flash('success', "Product Line added successfully");
        return back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductLine  $productLine
     * @return \Illuminate\Http\Response
     */
    public function show(ProductLine $productLine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductLine  $productLine
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductLine $productLine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!\Auth::user()->checkAccess("24", "E")) {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //validate request
        $this->validate($request, [
            'editProductLineName' => 'required',
        ]);

        //if validator passed store service item
        $product = ProductLine::where('ProdLine_ID', $id)->first();
        $product->Product = $request->input('editProductLineName');
        $product->save();

        \Session::flash('success', "Product Line updated successfully");
        return redirect()->route('productlines.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!\Auth::user()->checkAccess("24", "D"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        $success = ProductLine::where('ProdLine_ID', $id)->delete();
        if($success){
            \Session::flash('success', "Product Line deleted successfully");
            return redirect()->route('productlines.index');
        }
    }
}
