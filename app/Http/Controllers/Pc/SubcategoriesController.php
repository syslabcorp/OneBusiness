<?php

namespace App\Http\Controllers\Pc;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Corporation;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Datetime;

class SubcategoriesController extends Controller
{
  public function store(Request $request) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\Pc\Subcat;
    $categoryModel->setConnection($company->database_name);


    $subcategory = $categoryModel->create($request->subcat);
    $categoryId = $subcategory->category->cat_id;

    foreach($request->branches as $key => $branch) {
      if(array_key_exists('checked', $branch) && $branch['checked'] == 1) {
        $subcategory->branches()->create([
          'sat_branch' => $branch['sat_branch'],
          'cat_id' => $categoryId
        ]);
      }
    }

    \Session::flash('success', "Subcategory successfully created!");

    return redirect(route('petycash.index', [
      'corpID' => $request->corpID,
      'tab' => 'sub',
      'categoryId' => $categoryId
    ]));
  }

  public function update(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\Pc\Subcat;
    $categoryModel->setConnection($company->database_name);

    $subcategory = $categoryModel->find($id);
    $subcategory->update($request->subcat);
    $subcategory->branches()->delete();
    $categoryId = $subcategory->category->cat_id;

    foreach($request->branches as $key => $branch) {
      if(array_key_exists('checked', $branch) && $branch['checked'] == 1) {
        $subcategory->branches()->create([
          'sat_branch' => $branch['sat_branch'],
          'cat_id' => $categoryId
        ]);
      }
    }

    \Session::flash('success', "Subcategory successfully updated!");

    return redirect(route('petycash.index', [
      'corpID' => $request->corpID,
      'tab' => 'sub',
      'categoryId' => $categoryId
    ]));
  }

  public function destroy(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\Pc\Subcat;
    $categoryModel->setConnection($company->database_name);

    $subcategory = $categoryModel->findOrFail($id);
    $subcategory->update(['deleted' => 1]);

    \Session::flash('success', "Subcategory successfully deleted!");

    return redirect(route('petycash.index', [
      'corpID' => $request->corpID,
      'tab' => 'sub',
      'categoryId' => $subcategory->category->cat_id
    ]));
  }
}
