<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\Corporation;

class PayrollsController extends Controller
{
    public function index(Request $request)
    {
        $companies = Corporation::where('status', 1)->where('database_name', '<>', '')
                                 ->orderBy('corp_name')->get();

        $corpID = request()->corpID ?: $companies->first()->corp_id;

        if(!\Auth::user()->checkAccessByIdForCorp($corpID, 39, 'V')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $company = Corporation::findOrFail($corpID);
        $status = isset($request->status) ? $request->status : '1';
        $tab = $request->tab ? $request->tab : 'deduct';

        $deductModel = new \App\Models\Py\DeductMstr;
        $deductModel->setConnection($company->database_name);

        $benfModel = new \App\Models\Py\BenfMstr;
        $benfModel->setConnection($company->database_name);

        $expModel = new \App\Models\Py\ExpMstr;
        $expModel->setConnection($company->database_name);

        $deductItems = $deductModel->where('active', $status)
                        ->orderBy('description', 'ASC')
                        ->get();

        $benfItems = $benfModel->where('active', $status)
                            ->orderBy('description', 'ASC')
                            ->get();

        $expItems = $expModel->where('active', $status)
                            ->orderBy('description', 'ASC')
                            ->get();
        
        $deductItem = $deductItems->first();
        if($request->tab == 'deduct') {
            if($request->item) {
                $deductItem = $deductModel->find($request->item);
            }
            if($request->action == 'new' || !$deductItem) {
                $deductItem = $deductModel;
            }
        }

        $benfItem = $benfItems->first();

        if($request->tab == 'benefit') {
            if($request->item) {
                $benfItem = $benfModel->find($request->item);
            }
            if($request->action == 'new' || !$benfItem) {
                $benfItem = $benfModel;
            }
        }

        $expItem = $expItems->first();

        if($request->tab == 'expense') {
            if($request->item) {
                $expItem = $expModel->find($request->item);
            }
            if($request->action == 'new' || !$expItem) {
                $expItem = $expModel;
            }
        }

        $columnOptions = [
            'Basic Pay', 'Substitutions Pay', 'Night Diff', 'SSS', 'PHIC',
            'Pag-ibig', 'Tardiness', 'Uniform', 'Bond', 'Insurance', 'Shortages',
            'Wrong Input', 'Lost Hardware', 'SSS Loans', 'Others', 'COLA',
            'Allowance', 'Refunds'
        ];

        $periodOptions = [
            'Reg Hours', 'Day', 'Week', 'Payroll', 'Monthly Basic Pay',
            'Hours Overtime', 'Hours Premium', 'Hours Undertime', 'Shortage',
            'Wrong Remittance', 'Reg Hours + Overtime'
        ];

        return view('payrolls.index', [
            'tab' => $tab,
            'corpID' => $corpID,
            'companies' => $companies,
            'columnOptions' => $columnOptions,
            'periodOptions' => $periodOptions,
            'status' => $status,
            'deductItems' => $deductItems,
            'deductItem' => $deductItem,
            'benfItems' => $benfItems,
            'benfItem' => $benfItem,
            'expItems' => $expItems,
            'expItem' => $expItem,
            'action' => $request->action
        ]);
    }

    public function deduct(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);

        $deductModel = new \App\Models\Py\DeductMstr;
        $deductModel->setConnection($company->database_name);
        $deductParams = $request->only([
            'description', 'type', 'fixed_amt', 'total_amt', 'period',
            'incl_gross', 'active', 'category'
        ]);
        $deductParams['fixed_amt'] = preg_replace("/\,/", '', $deductParams['fixed_amt']);
        $deductParams['total_amt'] = preg_replace("/\,/", '', $deductParams['total_amt']);

        if($request->id) {
            $deductItem = $deductModel->find($request->id);
            $deductItem->update($deductParams);
            \Session::flash('success', "Deduction #{$deductItem->ID_deduct} has been updated.");
        }else {
            $deductItem = $deductModel->create($deductParams);
            \Session::flash('success', "New deduction category has been created.");
        }

        $deductItem->details()->delete();
        if($request->details) {
            foreach($request->details as $detail) {
                $deductItem->details()->create($detail);
            }
        }
        

        return redirect(route('payrolls.index', [
            'corpID' => $request->corpID,
            'status' => $deductItem->active,
            'tab' => 'deduct',
            'item' => $deductItem->ID_deduct
        ]));
    }

    public function benefit(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);

        $benfModel = new \App\Models\Py\BenfMstr;
        $benfModel->setConnection($company->database_name);

        $benfParams = $request->only([
            'description', 'type', 'fixed_amt', 'perctg', 'period',
            'incl_gross', 'active', 'category'
        ]);

        $benfParams['fixed_amt'] = preg_replace("/\,/", '', $benfParams['fixed_amt']);
        $benfParams['perctg'] = preg_replace("/\,/", '', $benfParams['perctg']);

        if($request->id) {
            $benfItem = $benfModel->find($request->id);
            $benfItem->update($benfParams);
            \Session::flash('success', "Benefit #{$benfItem->ID_benf} has been updated.");
        }else {
            $benfItem = $benfModel->create($benfParams);
            \Session::flash('success', "New benefit category has been created.");
        }

        $benfItem->details()->delete();
        if($request->details) {
            foreach($request->details as $detail) {
                $benfItem->details()->create($detail);
            }
        }

        
        
        return redirect(route('payrolls.index', [
            'corpID' => $request->corpID,
            'status' => $benfItem->active,
            'tab' => 'benefit',
            'item' => $benfItem->ID_benf
        ]));
    }

    public function expense(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);

        $expModel = new \App\Models\Py\ExpMstr;
        $expModel->setConnection($company->database_name);

        $expParams = $request->only([
            'description', 'type', 'fixed_amt', 'perctg', 'period',
            'incl_gross', 'active', 'category'
        ]);

        $expParams['fixed_amt'] = preg_replace("/\,/", '', $expParams['fixed_amt']);
        $expParams['perctg'] = preg_replace("/\,/", '', $expParams['perctg']);

        if($request->id) {
            $expItem = $expModel->find($request->id);
            $expItem->update($expParams);
            \Session::flash('success', "Expense #{$expItem->ID_exp} has been updated.");
        }else {
            $expItem = $expModel->create($expParams);
            \Session::flash('success', "New expense category has been created.");
        }

        $expItem->details()->delete();
        
        if($request->details) {
            foreach($request->details as $detail) {
                $expItem->details()->create($detail);
            }
        }

        return redirect(route('payrolls.index', [
            'corpID' => $request->corpID,
            'status' => $expItem->active,
            'tab' => 'expense',
            'item' => $expItem->ID_exp
        ]));
    }
}
