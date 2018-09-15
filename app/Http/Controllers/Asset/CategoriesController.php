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
        $categories = Category::all();

        return view('assets.categories.index', [
            'categories' => $categories
        ]);
    }
    public function store(Request $request)
    {
        Category::create($request->only('description'));
        \Session::flash('success','Category has been created successfully');

        return redirect()->back();
    }
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['success' => true]);
    }
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->update($request->only('description'));
        \Session::flash('success','Category has been updated successfully');

        return redirect()->back();
    }

}
