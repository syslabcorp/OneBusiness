<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Corporation;
use DB;
use Validator;
use Datetime;

class SubcategoriesController extends Controller
{
  public function index(Request $request) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HCategory;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->find($request->id);

    return view('subcategories.index', [
      'category' => $category
    ]);
  }

  public function store(Request $request) {
    // if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'E')) {
    //   \Session::flash('error', "You don't have permission"); 
    //   return redirect("/home"); 
    // }

    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HCategory;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->find($request->category_id);

    $category->subcategories()->create($request->only(['expires', 'description', 'mutli_doc']));

    \Session::flash('success', "Subcategory successfully created!");

    return redirect(route('categories.index', ['corpID' => $request->corpID]));
  }

  public function update(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HSubcategory;
    $categoryModel->setConnection($company->database_name);

    $subcategory = $categoryModel->find($id);
    $subcategory->update($request->only(['expires', 'description', 'mutli_doc']));

    \Session::flash('success', "Subcategory successfully updated!");

    return redirect(route('categories.index', ['corpID' => $request->corpID]));
  }

  public function destroy(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HSubcategory;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->findOrFail($id);
    $category->update(['Deleted' => 1]);

    \Session::flash('success', "Subcategory successfully deleted!");

    return redirect(route('categories.index', ['corpID' => $request->corpID]));
  }
}
