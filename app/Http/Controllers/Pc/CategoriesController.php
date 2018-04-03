<?php

namespace App\Http\Controllers\Pc;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\Corporation;
use DB;
use Validator;
use Datetime;

class CategoriesController extends Controller {

  public function store(Request $request) {

    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\Pc\Cat;
    $categoryModel->setConnection($company->database_name);
    $category = $categoryModel->create($request->cat);
    $category->subcategories()->create($request->subcat);

    \Session::flash('success', "Category successfully created!");

    return redirect(route('petycash.index', ['corpID' => $request->corpID, 'tab' => 'cat']));
  }

  public function update(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\Pc\Cat;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->findOrFail($id);
    $category->update($request->cat);

    \Session::flash('success', "Category successfully updated!");

    return redirect(route('petycash.index', ['corpID' => $request->corpID, 'tab' => 'cat']));
  }

  public function destroy(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\Pc\Cat;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->findOrFail($id);
    $category->update(['deleted' => 1]);

    \Session::flash('success', "Category successfully deleted!");

    return redirect(route('petycash.index', ['corpID' => $request->corpID, 'tab' => 'cat']));
  }
}
