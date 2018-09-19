<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Company;
use Illuminate\Http\Request;
use App\Models\Corporation;
use App\Models\Equip\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        if(!\Auth::user()->checkAccessById(55, 'V')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $categories = Category::all();

        return view('assets.categories.index', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        if(!\Auth::user()->checkAccessById(55, 'A')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        Category::create($request->only('description'));
        \Session::flash('success','Category has been created successfully');

        return redirect()->back();
    }

    public function destroy($id)
    {
        if(!\Auth::user()->checkAccessById(55, 'D')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        if(!\Auth::user()->checkAccessById(55, 'E')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $category = Category::findOrFail($id);
        $category->update($request->only('description'));
        \Session::flash('success','Category has been updated successfully');

        return redirect()->back();
    }

}
