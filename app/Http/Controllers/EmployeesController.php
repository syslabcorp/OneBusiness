<?php

namespace App\Http\Controllers;

use App\User;
use App\Corporation;
use App\Branch;
use DB;
use Validator;
use Datetime;
use App\Transformers\Py\EmpTransformer;
use Config;

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

    $branches =  DB::connection($company->database_name)->table('py_emp_hist')
        ->join(Config::get('database.connections.mysql.database').".t_users", 'py_emp_hist.EmpID', '=', 't_users.UserID')
        ->join(Config::get('database.connections.mysql.database').".t_sysdata", 'py_emp_hist.Branch', '=', 't_sysdata.Branch')
        ->select('t_sysdata.Branch', 'ShortName')
        ->distinct()->get();
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

    // $items = DB::connection($company->database_name)->table('py_emp_hist')
    //     ->join(Config::get('database.connections.mysql.database').".t_users", 'py_emp_hist.EmpID', '=', 't_users.UserID')
    //     ->join(Config::get('database.connections.mysql.database').".t_sysdata", 'py_emp_hist.Branch', '=', 't_sysdata.Branch');
    // dd($items->get());

    $items = User::all();

    switch($status) {
      case "1":
          $items = $items->where('SQ_Active', 1);
          break;
      case "2":
          $items = $items->where('SQ_Active', 0);
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
        $empHistory = $empHistoryModel->where('Branch', $branch)->get()->pluck('EmpID')->toArray();
        $items = $items->whereIn('UserID', $empHistory);
      }
    }

    $user_by_branches = $empHistoryModel->join(Config::get('database.connections.mysql.database').'.t_sysdata', 't_sysdata.Branch', '=', 'py_emp_hist.Branch')->get()->pluck('EmpID')->toArray();


    switch($level) {
      case "non-branch":
        $items = $items->where('SQ_Branch',  0);
        break;
      case "branch":
        $items = $items->where('SQ_Branch', '!=', 0);
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

  public function transfer(Request $request, $id)
  {
      $company = Corporation::findOrFail($request->corpID);

      $hdrModel = new \App\Models\Stxfr\Hdr;
      $hdrModel->setConnection($company->database_name);

      $detailModel = new \App\Models\Stxfr\Detail;
      $detailModel->setConnection($company->database_name);

      $rcvModel = new \App\Srcvdetail;
      $rcvModel->setConnection($company->database_name);

      $spoModel = new \App\Models\Spo\Hdr;
      $spoModel->setConnection($company->database_name);

      $stockItem = $spoModel->findOrFail($id);

      if($request->items) {
          foreach($request->items as $itemCode => $branches) {
              foreach($branches as $branch => $itemParams) {
                  if($itemParams['Qty'] == 0) {
                      continue;
                  }

                  $hdrItem = $hdrModel->create([
                      'Txfr_Date' => date('Y-m-d'),
                      'Txfr_To_Branch' => $branch,
                      'Rcvd' => 0,
                      'Uploaded' => 0
                  ]);

                  $rcvItems = $rcvModel->where('item_id', $itemParams['ItemId'])
                                  ->where('Bal', '>', 0)
                                  ->orderBy('RcvDate', 'ASC')
                                  ->get();

                  $itemQtyRemaining = $itemParams['Qty'];
                  foreach($rcvItems as $rcvItem) {
                      $itemQty = $itemQtyRemaining;
                      $itemQtyRemaining -= $rcvItem->Bal;
                      if($itemQty <= $rcvItem->Bal) {
                          $rcvItem->update(['Bal' => $rcvItem->Bal - $itemQty]);
                      }else {
                          $itemQty = $rcvItem->Bal;
                          $rcvItem->update(['Bal' => 0]);
                      }

                      $detailModel->create([
                          'Txfr_ID' => $hdrItem->Txfr_ID,
                          'item_id' => $itemParams['ItemId'],
                          'ItemCode' =>$itemCode,
                          'Qty' => $itemQty,
                          'Bal' => $itemQty,
                          'Movement_ID' => $rcvItem->Movement_ID,
                      ]);

                      if($itemQtyRemaining <= 0) {
                          break;
                      }
                  }

                  $poItems = $stockItem->items()
                                       ->whereRaw('ServedQty < Qty')
                                       ->where('item_id', $itemParams['ItemId'])
                                       ->where('Branch', $branch)
                                       ->get();

                  $itemQtyRemaining = $itemParams['Qty'];
                  foreach($poItems as $poItem) {
                      $itemQty = $itemQtyRemaining;
                      $itemQtyRemaining -= $poItem->Qty - $poItem->ServedQty;

                      if($itemQty <= $poItem->Qty - $poItem->ServedQty) {
                          $poItem->update(['ServedQty' => $poItem->ServedQty + $itemQty]);
                      }else {
                          $poItem->update(['ServedQty' => $poItem->Qty]);
                      }

                      if($itemQtyRemaining <= 0) {
                          break;
                      }
                  }
              }
          }
      }

    return response()->json([
      'success'=> 'success'
    ]);
  }



    public function edit(Request $request, $id)
    {
        if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 42, 'E')) {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        $company = Corporation::findOrFail($request->corpID);

        $hdrModel = new \App\Models\Stxfr\Hdr;
        $hdrModel->setConnection($company->database_name);

        $hdrItem = $hdrModel->findOrFail($id);

        $cfgModel = new \App\Models\SItem\Cfg;
        $cfgModel->setConnection($company->database_name);

        $rcvModel = new \App\Srcvdetail;
        $rcvModel->setConnection($company->database_name);

        $suggestItems = $cfgModel->where('Active', 1)
                                ->distinct()
                                ->orderBy('ItemCode', 'ASC')
                                ->get();


        $branches = $company->branches()->where('Active', 1)
                            ->orderBy('ShortName', 'ASC')
                            ->get();

        return view('stocktransfer.edit', [
            'branches' => $branches,
            'corpID' => $request->corpID,
            'suggestItems' => $suggestItems,
            'hdrItem' => $hdrItem,
            'rcvModel' => $rcvModel,
            'stockStatus' => $request->stockStatus
        ]);
    }

    public function update(Request $request, $id)
    {
        $company = Corporation::findOrFail($request->corpID);

        $hdrModel = new \App\Models\Stxfr\Hdr;
        $hdrModel->setConnection($company->database_name);

        $rcvModel = new \App\Srcvdetail;
        $rcvModel->setConnection($company->database_name);

        $hdrItem = $hdrModel->findOrFail($id);

        $hdrItem->update($request->only([
            'Txfr_Date', 'Txfr_To_Branch'
        ]));

        foreach($hdrItem->details as $detail) {
            $rcvItem = $rcvModel->where('Movement_ID', $detail->Movement_ID)
                            ->first();
            if($rcvItem) {
                $rcvItem->update(['Bal' => $rcvItem->Bal + $detail->Bal]);
            }
        }
        $hdrItem->details()->delete();

        if($request->details) {
            foreach($request->details as $itemParams) {
                $rcvItems = $rcvModel->where('item_id', $itemParams['item_id'])
                                    ->where('Bal', '>', 0)
                                    ->orderBy('RcvDate', 'ASC')
                                    ->get();
                $itemQtyRemaining = $itemParams['Qty'];
                foreach($rcvItems as $rcvItem) {
                    $itemQty = $itemQtyRemaining;
                    $itemQtyRemaining -= $rcvItem->Bal;
                    if($itemQty <= $rcvItem->Bal) {
                        $rcvItem->update(['Bal' => $rcvItem->Bal - $itemQty]);
                    }else {
                        $itemQty = $rcvItem->Bal;
                        $rcvItem->update(['Bal' => 0]);
                    }
                    $hdrItem->details()->create([
                        'item_id' => $itemParams['item_id'],
                        'ItemCode' => $itemParams['ItemCode'],
                        'Qty' => $itemQty,
                        'Bal' => $itemQty,
                        'Movement_ID' => $rcvItem->Movement_ID,
                    ]);
                    if($itemQtyRemaining <= 0) {
                        break;
                    }
                }
            }
        }


        \Session::flash('success', "Stock item has been updated successfully");

        return redirect(route('stocktransfer.index', [
            'corpID' => $request->corpID,
            'tab' => 'stock',
            'stockStatus' => $request->stockStatus
        ]));
    }
}
