<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\Corporation;

class DepartmentsController extends Controller
{
    public function index(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);
      
        $deductModel = new \App\Models\Py\DeductMstr;
        $deductModel->setConnection($company->database_name);

        return view('departments.index', [
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
        }else {
            $benfItem = $benfModel->create($benfParams);
        }

        $benfItem->details()->delete();
        if($request->details) {
            foreach($request->details as $detail) {
                $benfItem->details()->create($detail);
            }
        }

        \Session::flash('success', "Benefit #{$benfItem->ID_benf} has been updated.");

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
        }else {
            $expItem = $expModel->create($expParams);
        }

        $expItem->details()->delete();
        
        if($request->details) {
            foreach($request->details as $detail) {
                $expItem->details()->create($detail);
            }
        }

        \Session::flash('success', "Expense #{$expItem->ID_exp} has been updated.");

        return redirect(route('payrolls.index', [
            'corpID' => $request->corpID,
            'status' => $expItem->active,
            'tab' => 'expense',
            'item' => $expItem->ID_exp
        ]));
    }
}
