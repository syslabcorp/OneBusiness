<?php

namespace App\Http\Controllers;

use App\User;
use App\Corporation;
use App\Branch;
use DB;
use Validator;
use Datetime;
use App\Transformers\Py\EmpTransformer;
use App\Transformers\H\DocTransformer;
use App\Transformers\Py\PositionTransformer;
use App\Transformers\WageTmpl8\WageTransformer;
use Config;
use App\HDocs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmployeesController extends Controller {
  public function index(Request $request) {
    $company = Corporation::findOrFail($request->corpID);

    $branchSelect = $request->selectBranch ? $request->selectBranch : false;
    $branch = $request->branch ? $request->branch : 1;
    $status= $request->status ? $request->status : 1;
    $level = $request->level ? $request->level : 'non-branch';

    // $items = User::all();

    // $empHist = EmpHistories($this->database_name);

    // $branches =  DB::connection($company->database_name)->table('py_emp_hist')
    //     ->join(Config::get('database.connections.mysql.database').".t_users", 'py_emp_hist.EmpID', '=', 't_users.UserID')
    //     ->join(Config::get('database.connections.mysql.database').".t_sysdata", 'py_emp_hist.Branch', '=', 't_sysdata.Branch')
    //     ->select('t_sysdata.Branch', 'ShortName')
    //     ->distinct()->get();
    $branchList = User::all()->pluck('Branch');
    $sqBranchList = User::all()->pluck('Branch');
    

    $branches = Branch::whereIn('Branch', $branchList)->orWhereIn('Branch', $sqBranchList)->distinct()->get();
    // dd($branches);
    // DB::connection($company->database_name)->table('py_emp_hist')
    //   ->join(Config::get('database.connections.mysql.database').".t_sysdata", 'py_emp_hist.Branch', '=', 't_sysdata.Branch')
    //   ->get();
    return view('employees/index', [
      'corpID' => $request->corpID,
      'branchSelect' => $branchSelect,
      'status' => $status,
      'level' => $level,
      'branch' => $branch,
      'branches' => $branches
    ]);
  }

  public function deliveryItems(Request $request)
  {
    $company = Corporation::findOrFail($request->corpID);

    $branchSelect = $request->branchSelect ? $request->branchSelect : false;
    $branch = $request->branch ? $request->branch : 1;
    $status= $request->status ? $request->status : 1;
    $level = $request->level ? $request->level : "non-branch";
    $order = $request->order ? $request_order : "";

    // $items = DB::connection($company->database_name)->table('py_emp_hist')
    //     ->join(Config::get('database.connections.mysql.database').".t_users", 'py_emp_hist.EmpID', '=', 't_users.UserID')
    //     ->join(Config::get('database.connections.mysql.database').".t_sysdata", 'py_emp_hist.Branch', '=', 't_sysdata.Branch');
    // dd($items->get());

    $items = User::all();

    switch($status) {
      case "1":
          $items = $items->filter(function($item){
            return ($item->Active == 1) || ($item->SQ_Active == 1) || ($item->TechActive == 1);
          });
          break;
      case "2":
          $items = $items->where('SQ_Active', 0)->where('Active', 0)->where('TechActive', 0);
          break;
      default:
          break;
    }

    $empHistoryModel = new \App\Models\Py\EmpHistory;
    $empHistoryModel->setConnection($company->database_name);

    if($branchSelect && $branchSelect == "hasBranch")
    {
      if($branch)
      {

        // $empHistory = $empHistoryModel->where('Branch', $branch)->get()->pluck('EmpID')->toArray();
        // $items = $items->whereIn('UserID', $empHistory);

        $items = $items->filter(function($item) use ($branch){
          return ($item->Branch == $branch) || ($item->SQ_Branch == $branch);
        });
      }
    }

    $user_by_branches = $empHistoryModel->join(Config::get('database.connections.mysql.database').'.t_sysdata', 't_sysdata.Branch', '=', 'py_emp_hist.Branch')->get()->pluck('EmpID')->toArray();


    switch($level) {
      case "non-branch":
        $items = $items->where('level_id', '>', 9);
        break;
      case "branch":
        $items = $items->where('level_id', '<=', 9);
        break;
      default:
        break;
    }

    // if($request->selectBranch)
    // {
    //   $items = $items->where('Branch', $branch);
    // }
    // return response()->json($branchSelect);

    return fractal($items, new EmpTransformer($company->database_name))->toJson();
  }

  public function deliveryDocuments(Request $request, $id)
  {
    $company = Corporation::findOrFail($request->corpID);

    $user = User::find($id);

    $docModel = new \App\HDocs;
    $docModel->setConnection($company->database_name);
    $items = $docModel->where('emp_id', $user->UserID)->get();

    return fractal($items, new DocTransformer)->toJson();
  }

  public function show(Request $request, $id)
  {
    $tab = "auto";
    $user = User::find($id);
    $company = Corporation::findOrFail($request->corpID);
    return view('employees/show', [
      'corpID' => $request->corpID,
      'tab' => $tab,
      'user' => $user,
    ]);
  }

  public function update(Request $request, $id)
  {
    dd($request->all());
    $company = Corporation::findOrFail($request->corpID);
    $user = User::find($id);
  }

  private function empParams()
  {
      
      $params = request()->only(['FIrstName', 'MiddleName', 'LastName', 'SuffixName', 'Address', 'Position', 'TIN']);
      $params['main'] = $params['main'] ?: 0;

      return $params;
  }

  public function deliveryPositions(Request $request, $id)
  {
    $company = Corporation::findOrFail($request->corpID);

    $user = User::find($id);

    $empHistoryModel = new \App\Models\Py\EmpHistory;
    $empHistoryModel->setConnection($company->database_name);
    $items = $empHistoryModel->where('EmpID', $user->UserID)->get();

    return fractal($items, new PositionTransformer)->toJson();
  }

  public function deliveryWages(Request $request, $id)
  {
    $company = Corporation::findOrFail($request->corpID);
    $user = User::find($id);

    $empHistoryModel = new \App\Models\Py\EmpHistory;
    $empHistoryModel->setConnection($company->database_name);
    $empHist = $empHistoryModel->where('EmpID', $user->UserID)->get();
    $empRateModel = new \App\Models\Py\EmpRate;
    $empRateModel->setConnection($company->database_name);

    $items = $empRateModel->whereIn('txn_id', $empHist->pluck('txn_id') )->get();
    // dd($items);

    return fractal($items, new WageTransformer($company->database_name, $user))->toJson();
  }
}
