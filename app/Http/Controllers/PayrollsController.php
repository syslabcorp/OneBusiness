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
        $company = Corporation::findOrFail($request->corpID);
        $status = isset($request->status) ? $request->status : '1';
        $tab = $request->tab ? $request->tab : 'deduct';

        $deductModel = new \App\Models\Deduct\Mstr;
        $deductModel->setConnection($company->database_name);

        $deductItems = $deductModel->where('active', $status)
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
            'corpID' => $request->corpID,
            'columnOptions' => $columnOptions,
            'periodOptions' => $periodOptions,
            'status' => $status,
            'deductItems' => $deductItems,
            'deductItem' => $deductItem,
            'action' => $request->action
        ]);
    }

    public function deduct(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);

        $deductModel = new \App\Models\Deduct\Mstr;
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
        }else {
            $deductItem = $deductModel->create($deductParams);
        }

        $deductItem->details()->delete();
        if($request->details) {
            foreach($request->details as $detail) {
                $deductItem->details()->create($detail);
            }
        }

        \Session::flash('success', "Deduction #{$deductItem->ID_deduct} has been updated.");

        return redirect(route('payrolls.index', [
            'corpID' => $request->corpID,
            'status' => $deductItem->active,
            'tab' => $request->tab,
            'item' => $deductItem->ID_deduct
        ]));
    }
}
