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

    $branches = collect([]);

    if (\Auth::user()->area) {
        $branches = $company->branches()
                            ->where('Active', '1')
                            ->whereIn('Branch', explode(',', \Auth::user()->area->branch))
                            ->orderBy('ShortName', 'ASC')
                            ->get();
    }

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

    $branchSelect = $request->branchSelect ? $request->branchSelect : null;
    $branch = $request->branch ? $request->branch : 1;
    $status= $request->status ? $request->status : 1;
    $level = $request->level ? $request->level : "non-branch";
    $order = $request->order ? $request_order : "";

    $branchIDs = [];

    if (\Auth::user()->area) {
        $branchIDs = $company->branches()
                            ->where('Active', '1')
                            ->whereIn('Branch', explode(',', \Auth::user()->area->branch))
                            ->pluck('Branch');
    }

    $items = User::orderBy('UserName', 'ASC')
                ->whereIn('Branch', $branchIDs)
                ->whereIn('SQ_Branch', $branchIDs, 'OR')
                ->get();

    switch($status) {
        case "1":
            $items = $items->filter(function($item){
                return ($item->Active == 1) || ($item->SQ_Active == 1);
            });
            break;
        case "2":
            $items = $items->filter(function($item){
                return ($item->Active == 0) && ($item->SQ_Active == 0);
            });
            break;
        default:
            break;
    }

    if($branch && $branchSelect == "hasBranch")
    {
        $items = $items->filter(function($item) use ($branch, $status) {
            switch ($status) {
                case "1":
                    return $item->Active == 1 && $item->Branch == $branch || $item->SQ_Active == 1 && $item->SQ_Branch == $branch;
                    break;
                case "2":
                    return $item->Active == 0 && $item->Branch == $branch || $item->SQ_Active == 0 && $item->Branch == $branch;
                    break;
                case "all":
                    return $item->Branch == $branch || $item->SQ_Branch == $branch;
                    break;
                default:
                    return false;
                    break;
            }
        });
    }

    // Disable level filter if enable select branch
    if ($branchSelect != "hasBranch") {
        switch($level) {
            case "non-branch":
                $items = $items->filter(function($item){
                    return $item->level_id > 9;
                });
                break;
            case "branch":
                $items = $items->filter(function($item){
                    return $item->level_id <= 9;
                });
                break;
            default:
                break;
        }
    }

    return fractal($items, new EmpTransformer($company->database_name))->toJson();
  }

  public function deliveryDocuments(Request $request, $id)
  {
    $company = Corporation::findOrFail($request->corpID);

    $user = User::find($id);

    $docModel = new \App\HDocs;
    $docModel->setConnection($company->database_name);
    $items = $docModel->where('emp_id', $user->UserID);

    if (request()->document) {
        $items = $items->where('subcat_id', request()->document);
    }

    if (request()->category) {
        $items = $items->where('doc_no', request()->category);
    }

    return fractal($items->get(), new DocTransformer)->toJson();
  }

  public function show(Request $request, $id)
  {
    $tab = request()->tab ? request()->tab : "auto";
    $user = User::find($id);
    $company = Corporation::findOrFail($request->corpID);

    $shortageItems = collect([]);
    $tardinessItems = collect([]);
    $shiftModel = new \App\Shift;
    $shiftRelationshipKey = 'ShiftOwner';
    $shiftDateField = 'ShiftDate';
    $remittanceTable = 't_remitance';

    $tardinessModel = new \App\Models\T\Dtr;
    $tardinessModel->setConnection($company->database_name);

    if ($company->database_name == 'k_master') {
      $shiftModel = new \App\KShift;
      $shiftRelationshipKey = 'user_id';
      $shiftDateField = 'shift_start';
      $remittanceTable = 'remittance';
    }

    if (request()->from_date && request()->to_date) {
      $shiftModel->setConnection($company->database_name);
      $shortageItems = $shiftModel->where($shiftRelationshipKey, '=', $id)
                          ->selectRaw("*, CAST($shiftDateField AS DATE) AS ShiftDate")
                          ->leftJoin($remittanceTable, $remittanceTable . '.Shift_ID', '=', $shiftModel->getTable() . '.Shift_ID')
                          ->where('Adj_Amt', '!=', '0')
                          ->whereDate($shiftDateField, '>=', request()->from_date)
                          ->whereDate($shiftDateField, '<=', request()->to_date)
                          ->orderBy('ShiftDate', 'DESC')
                          ->get();

        $shortageItems = $shortageItems->map(function($shift) {
            $shiftDate = new DateTime($shift->ShiftDate);
            if ($shiftDate->format('d') <= 15 ) {
                $shift->order = $shiftDate->format('Y-m-1');
                $shift->period = $shiftDate->format('m/1/Y') . ' - ' . $shiftDate->format('m/15/Y');
            } else {
                $shift->order = $shiftDate->format('Y-m-16');
                $shift->period = $shiftDate->format('m/16/Y') . ' - ' . $shiftDate->format('m/t/Y');
            }

            return $shift;
        });

        $shortageItems = $shortageItems->groupBy('period');

        $tardinessItems = $tardinessModel
                          ->where('late_hrs', '>', 0)
                          ->whereDate('TimeIn', '>=', request()->from_date)
                          ->whereDate('TimeIn', '<=', request()->to_date)
                          ->where('UserId', '=', $id)
                          ->orderBy('TimeIn', 'DESC')
                          ->get();

        $tardinessItems = $tardinessItems->map(function($shift) {
            $shiftDate = new DateTime($shift->TimeIn);
            if ($shiftDate->format('d') <= 15 ) {
                $shift->order = $shiftDate->format('Y-m-1');
                $shift->period = $shiftDate->format('m/1/Y') . ' - ' . $shiftDate->format('m/15/Y');
            } else {
                $shift->order = $shiftDate->format('Y-m-16');
                $shift->period = $shiftDate->format('m/16/Y') . ' - ' . $shiftDate->format('m/t/Y');
            }

            return $shift;
        });

        $tardinessItems = $tardinessItems->groupBy('period');
    }

    $empRateModel = new \App\Models\Py\EmpRate;
    $empRateModel->setConnection($company->database_name);

    $empRateItem =  $empRateModel->join('py_emp_hist', 'py_emp_hist.txn_id', '=', 'py_emp_rate.txn_id')
                            ->where('py_emp_hist.EmpID', '=', $id)
                            ->first();
    $template = $empRateItem ? $empRateItem->mstr : null;

    $templateModel = new \App\Models\WageTmpl8\Mstr;
    $templateModel->setConnection($company->database_name);

    $templates = $templateModel->orderBy('code')->get();

    $recommendModel = new \App\Models\T\RecommendationRqst;
    $recommendModel->setConnection($company->database_name);

    $recommendItem = $recommendModel->where('userID', '=', $id)
                                    ->where('isApproved', 0)
                                    ->where('isDeleted', 0)
                                    ->first();
    $hcategoryModel = new \App\HCategory;
    $hcategoryModel->setConnection($company->database_name);
    $categories = $hcategoryModel->orderBy('description')->get();

    $hSubcategoryModel = new \App\HSubcategory;
    $hSubcategoryModel->setConnection($company->database_name);
    $subCategories = $hSubcategoryModel->orderBy('description')->get();

    $docModel = new \App\HDocs;
    $docModel->setConnection($company->database_name);
    $docItem = $docModel->where('emp_id', $user->UserID)
                        ->where('doc_no', 1)->where('subcat_id', 1)->first();

    $filename = $docItem ? $docItem->img_file : '';

    return view('employees/show', [
        'corpID' => $request->corpID,
        'tab' => $tab,
        'user' => $user,
        'shortageItems' => $shortageItems,
        'tardinessItems' => $tardinessItems,
        'template' => $template,
        'templates' => $templates,
        'recommendItem' => $recommendItem,
        'categories' => $categories,
        'subCategories' => $subCategories,
        'filename' => $filename
    ]);
  }

    public function update(Request $request, $id)
    {
        $company = Corporation::findOrFail($request->corpID);
        $user = User::find($id);

        $userParams = $request->only([
            'Address', 'TIN', 'Sex', 'Bday', 'SSS', 'PHIC', 'Pagibig', 'acct_no',
            'SuffixName', 'FirstName', 'MidName', 'LastName', 'split_type'
        ]);

        $userParams['updated_at'] = date('Y-m-d H:i:s');

        $user->update($userParams);

        \Session::flash('success', "Account information has been updated");

        return redirect(route('employee.show', [$user, 'corpID' => $request->corpID]));
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

    return fractal($items, new WageTransformer($company->database_name, $user))->toJson();
  }
    /**
     * Create new recommendation
     */
    public function recommendation($id)
    {
        $company = Corporation::findOrFail(request()->corpID);

        $recommendModel = new \App\Models\T\RecommendationRqst;
        $recommendModel->setConnection($company->database_name);

        $recommendParams = request()->only(['from_wage', 'to_wage', 'effective_date']);
        $recommendParams['userID'] = $id;
        $recommendParams['recommended_by'] = \Auth::user()->UserID;
        $recommendParams['date_recommended'] = date('Y-m-d H:i:s');
        $recommendParams['isApproved'] = 0;
        $recommendParams['isDeleted'] = 0;

        $recommendModel->create($recommendParams);

        return redirect(route('employee.show', [$id, 'corpID' => request()->corpID, 'tab' => 'wage']));
    }

    /**
     * Delete recommendation
     */
    public function deleteRecommendation($id)
    {
        $company = Corporation::findOrFail(request()->corpID);

        $recommendModel = new \App\Models\T\RecommendationRqst;
        $recommendModel->setConnection($company->database_name);

        $recommendItem = $recommendModel->findOrFail(request()->txn_no);
        $recommendItem->update(['isDeleted' => 1]);

        return redirect(route('employee.show', [$id, 'corpID' => request()->corpID, 'tab' => 'wage']));
    }

    /**
     * get modal template of document for Edit or Add
     * @return Template
     */
    public function documentModal($id)
    {
        $company = Corporation::findOrFail(request()->corpID);
        $user = User::findOrFail($id);

        $docModel = new \App\HDocs;
        $docItem = new \App\HDocs;
        $docModel->setConnection($company->database_name);

        if (request()->txn_no) {
            $docItem = $docModel->find(request()->txn_no);
        }

        $branches = $company->branches()
                        ->where('Active', '=', '1')
                        ->where('isMain', 1)
                        ->orderBy('ShortName', 'ASC')
                        ->get();

        if (\Auth::user()->level_id <= 9 && \Auth::user()->area) {
            $branches = $company->branches()
                            ->where('Active', '=', '1')
                            ->whereIn('Branch', explode(',', \Auth::user()->area->branch))
                            ->orderBy('ShortName', 'ASC')
                            ->get();
        }

        $branches = $branches->map(function($branch) use ($docItem, $user) {
            if (!$docItem->txn_no) {
                $branch->isChecked = $branch->Branch == $user->Branch && $user->Active || $branch->Branch == $user->SQ_Branch && $user->SQ_Active;
            }

            return $branch;
        });


        $hcategoryModel = new \App\HCategory;
        $hcategoryModel->setConnection($company->database_name);
        $categories = $hcategoryModel->orderBy('description')->get();
    
        $hSubcategoryModel = new \App\HSubcategory;
        $hSubcategoryModel->setConnection($company->database_name);
        $subCategories = $hSubcategoryModel->orderBy('description')->get();

        $docModel = new \App\HDocs;
        $docModel->setConnection($company->database_name);

        return view('employees.document-modal', [
            'user' => $user,
            'corpID' => request()->corpID,
            'branches' => $branches,
            'categories' => $categories,
            'subCategories' => $subCategories,
            'docItem' => $docItem
        ]);
    }

    /**
     * Render PDF for I/O
     */
    public function ioPDF()
    {
        $company = Corporation::findOrFail(request()->corpID);

        $branchIDs = Branch::where('corp_id', request()->corpID)->pluck('Branch');

        $users = User::orderBy('UserName', 'ASC')
                ->whereIn('Branch', $branchIDs)
                ->whereIn('SQ_Branch', $branchIDs, 'OR')
                ->get();
    
        $empHisModel = new \App\Models\Py\EmpHistory;
        $empHisModel->setConnection($company->database_name);

        foreach ($users as &$user) {
            $empHisItem = $empHisModel->where('EmpID', $user->UserID)
                                       ->orderBy('StartDate', 'DESC')
                                       ->first();

            $user->StartDate = $empHisItem ? ($empHisItem->StartDate ? $empHisItem->StartDate->format('d-M-Y') : '') : '';

            $template = $empHisModel->where('EmpID', $user->UserID)
                            ->join('py_emp_rate', 'py_emp_hist.txn_id', '=', 'py_emp_rate.txn_id')
                            ->join('wage_tmpl8_mstr', 'wage_tmpl8_mstr.wage_tmpl8_id', '=', 'py_emp_rate.wage_tmpl8_id')
                            ->first();
            $user->template = $template ? $template['position'] : '';
        }

        switch(request()->status) {
            case '1':
                $users = $users->filter(function($item){
                    return ($item->Active == 1) || ($item->SQ_Active == 1);
                });
                break;
            case '2':
                $users = $users->filter(function($item){
                    return ($item->Active == 0) && ($item->SQ_Active == 0);
                });
                break;
            default:
                break;
        }

        $users = $users->groupBy(function($user, $key) {
                    return $user->Branch ?: $user->SQ_Branch;
                });
        

        $pdf = \PDF::loadView('employees.io-pdf', ['users' => $users]);
        return $pdf->stream();
    }


    public function storeDocument($id)
    {
        $company = Corporation::findOrFail(request()->corpID);

        $docModel = new \App\HDocs;
        $docModel->setConnection($company->database_name);

        $latestDoc = $docModel->where('doc_no', request()->doc_no)
                              ->orderBy('series_no', 'DESC')
                              ->first();

        $docParams = request()->only(['branch', 'notes', 'doc_exp', 'subcat_id', 'doc_no']);
        $docParams['emp_id'] = $id;
        $docParams['series_no'] = $latestDoc ? $latestDoc->series_no + 1 : 1;
        $docParams['doc_exp'] = $docParams['doc_exp'] ?: '0000-00-00';
        $docParams['doc_date'] = date('Y-m-d');

        if (request()->hasFile('photo')) {
            $fileName = str_pad($docParams['doc_no'], 3, '0', STR_PAD_LEFT) .'-' . str_pad($docParams['subcat_id'], 3, '0', STR_PAD_LEFT) . '-' . $docParams['series_no'] . '.jpg';
            $docParams['img_file'] = $fileName;

            move_uploaded_file($_FILES['photo']['tmp_name'], $company->imgfile_path . '/' . $fileName);
        }

        $docModel->create($docParams);
        
        return redirect(route('employee.show', [$id, 'corpID' => request()->corpID, 'tab' => 'doc']));
    }

    public function updateDocument($id)
    {
        $company = Corporation::findOrFail(request()->corpID);

        $docModel = new \App\HDocs;
        $docModel->setConnection($company->database_name);

        $docItem = $docModel->find(request()->txn_no);


        $docParams = request()->only(['branch', 'notes', 'doc_exp', 'subcat_id', 'doc_no']);
        $docParams['doc_exp'] = $docParams['doc_exp'] ?: '0000-00-00';
        $docParams['doc_date'] = date('Y-m-d');

        if (request()->hasFile('photo')) {
            $fileName = str_pad($docParams['doc_no'], 3, '0', STR_PAD_LEFT) .'-' . str_pad($docParams['subcat_id'], 3, '0', STR_PAD_LEFT) . '-' . $docItem->series_no . '.jpg';
            $docParams['img_file'] = $fileName;

            move_uploaded_file($_FILES['photo']['tmp_name'], $company->imgfile_path . '/' . $fileName);
        }

        $docItem->update($docParams);
        
        return redirect(route('employee.show', [$id, 'corpID' => request()->corpID, 'tab' => 'doc']));
    }
}
