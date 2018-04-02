<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Corporation;
use DB;
use Validator;
use Datetime;

class CategoriesController extends Controller
{
  public function index(Request $request) {
    // if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'V')) {
    //   \Session::flash('error', "You don't have permission"); 
    //   return redirect("/home"); 
    // }

    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HCategory;
    $categoryModel->setConnection($company->database_name);

    $categories = $categoryModel->where('Deleted', '=', '0')
                                ->orderBy('description', 'asc')->get();


    return view('categories.index', [
      'categories' => $categories,
      'corpID' => $request->corpID
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

    $category = $categoryModel->create(['description' => $request->description, 'series' => 0]);
    \Session::flash('success', "Category successfully created!");

    return redirect(route('categories.index', ['corpID' => $request->corpID]));
  }

  public function update(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HCategory;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->findOrFail($id);
    $category->update(['description' => $request->description]);

    \Session::flash('success', "Category successfully updated!");

    return redirect(route('categories.index', ['corpID' => $request->corpID]));
  }

  public function destroy(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HCategory;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->findOrFail($id);
    $category->update(['Deleted' => 1]);

    \Session::flash('success', "Category successfully deleted!");

    return redirect(route('categories.index', ['corpID' => $request->corpID]));
  }

  public function petyCash(Request $request) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\Pc\Cat;
    $categoryModel->setConnection($company->database_name);

    $categories = $categoryModel->where('deleted', '=', '0')
                                ->orderBy('description', 'asc')->get();

    return view('categories.pety-cash', [
      'corpID' => $request->corpID,
      'categories' => $categories
    ]);
  }
}
