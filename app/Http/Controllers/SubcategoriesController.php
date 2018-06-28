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
      'category' => $category,
      'subcategoryId' => $request->subcategoryId
    ]);
  }

  public function store(Request $request) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HCategory;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->find($request->category_id);

    $subcategory = $category->subcategories()->create($request->only(['expires', 'description', 'multi_doc']));

    \Session::flash('success', "Subcategory successfully created!");

    return redirect(route('document-category.index', [
      'corpID' => $request->corpID,
      'categoryId' => $category->doc_no,
      'subcategoryId' => $subcategory->subcat_id
    ]));
  }

  public function update(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HSubcategory;
    $categoryModel->setConnection($company->database_name);

    $subcategory = $categoryModel->find($id);
    $subcategory->update($request->only(['expires', 'description', 'multi_doc']));

    \Session::flash('success', "Subcategory successfully updated!");

    return redirect(route('document-category.index', [
      'corpID' => $request->corpID,
      'categoryId' => $subcategory->category->doc_no,
      'subcategoryId' => $subcategory->subcat_id
    ]));
  }

  public function destroy(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HSubcategory;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->findOrFail($id);
    $category->update(['Deleted' => 1]);

    \Session::flash('success', "Subcategory successfully deleted!");

    return redirect(route('document-category.index', ['corpID' => $request->corpID]));
  }
}
