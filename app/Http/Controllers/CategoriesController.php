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
    if(!\Auth::user()->checkAccessById(33, 'V')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }

    $companies = Corporation::where('status', 1)->where('database_name', '<>', '')
                                 ->orderBy('corp_name')->get();

    $corpID = request()->corpID ?: $companies->first()->corp_id;

    $company = Corporation::find($corpID);

    $categoryModel = new \App\HCategory;
    $categoryModel->setConnection($company->database_name);

    $categories = $categoryModel->where('Deleted', '=', '0')
                                ->orderBy('description', 'asc')->get();


    return view('document-category.index', [
      'categories' => $categories,
      'corpID' => $corpID,
      'categoryId' => $request->categoryId,
      'subcategoryId' => $request->subcategoryId,
      'companies' => $companies
    ]);
  }

  public function store(Request $request) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HCategory;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->create(['description' => $request->description, 'series' => 0]);
    \Session::flash('success', "Category successfully created!");

    return redirect(route('document-category.index', ['corpID' => $request->corpID, 'categoryId' => $category->doc_no]));
  }

  public function update(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HCategory;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->findOrFail($id);
    $category->update(['description' => $request->description]);

    \Session::flash('success', "Category successfully updated!");

    return redirect(route('document-category.index', ['corpID' => $request->corpID, 'categoryId' => $category->doc_no]));
  }

  public function destroy(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\HCategory;
    $categoryModel->setConnection($company->database_name);

    $category = $categoryModel->findOrFail($id);
    $category->update(['Deleted' => 1]);

    \Session::flash('success', "Category successfully deleted!");

    return redirect(route('document-category.index', ['corpID' => $request->corpID]));
  }

  public function petyCash(Request $request) {
    if(!\Auth::user()->checkAccessById(32, 'V')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }

    $company = Corporation::findOrFail($request->corpID);

    $categoryModel = new \App\Pc\Cat;
    $categoryModel->setConnection($company->database_name);

    $branchModel = new \App\Pc\Branch;
    $branchModel->setConnection($company->database_name);

    $categories = $categoryModel->where('deleted', '=', '0')
                                ->orderBy('description', 'asc')->get();
    $branchs = $branchModel->where('active', '=', 1)
                           ->orderBy('short_name', 'asc')->get();

    return view('categories.pety-cash', [
      'corpID' => $request->corpID,
      'categories' => $categories,
      'branchs' => $branchs,
      'tab' => $request->tab,
      'categoryId' => $request->categoryId
    ]);
  }
}
