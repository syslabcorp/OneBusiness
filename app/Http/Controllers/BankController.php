<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankAccount;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!\Auth::user()->checkAccessById(27, "V"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //get records from t_sysdata
        $tSysdata = DB::table('t_sysdata')
            ->select('Active')
            ->orderBy('Branch', 'ASC')
            ->get();

        //get user data
        $branches = DB::table('user_area')
            ->where('user_ID', \Auth::user()->UserID)
            ->pluck('branch');

        $branch = explode(",", $branches[0]);



        //dd($branch);
        $corporations = DB::table('t_sysdata')
            ->join('corporation_masters', 't_sysdata.corp_id', '=', 'corporation_masters.corp_id')
            ->whereIn('t_sysdata.Branch', $branch)
            ->select('corporation_masters.corp_id', 'corporation_masters.corp_name')
            ->orderBy('corporation_masters.corp_name', 'ASC')
            ->distinct()
            ->get();


        $banks = BankAccount::orderBy('bank_id', 'ASC')->get();
        //get banks from db
        $selectBank = Bank::orderBy('bank_id', 'ASC')->get();

        $tSysdata = json_encode($tSysdata);

        return view('banks.index')
            ->with('selectBank', $selectBank)
            ->with('tSysData', json_encode($tSysdata))
            ->with('branch', $branch)
            ->with('corporations', $corporations)
            ->with('banks', $banks);
    }

    public function getBanksList(Request $request){
        $statusData = $request->input('dataStatus');
        $branch = $request->input('branch');
        $corpID = $request->input('corpId');
        $mainStatus = $request->input('MainStatus');

        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $columns = $request->input('columns');
        $orderable = $request->input('order');
        $orderNumColumn = $orderable[0]['column'];
        $orderDirection = $orderable[0]['dir'];
        $columnName = $columns[$orderNumColumn]['data'];
        $search = $request->input('search');

        //get data from sysdata
        /*$sysData = DB::table('t_sysdata')
            ->where('user_ID', \Auth::user()->UserID)
            ->pluck('branch');*/


        $banks = "";
        $recordsTotal = BankAccount::count();

        if($statusData != "" && $branch != "" && $corpID != "" && $mainStatus == "false"){
            $banks = DB::table('cv_bank_acct')
                ->join('cv_banks', 'cv_bank_acct.bank_id', '=', 'cv_banks.bank_id')
                ->join('t_sysdata', 'cv_bank_acct.branch', '=', 't_sysdata.Branch')
                ->where('cv_bank_acct.bank_id', $branch)
                ->where('t_sysdata.Active', $statusData)
                ->where('t_sysdata.corp_id', $corpID)
                ->orderBy($columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

        }else if($mainStatus != "false"){
            $banks = DB::table('cv_bank_acct')
                ->join('cv_banks', 'cv_bank_acct.bank_id', '=', 'cv_banks.bank_id')
                ->where('cv_bank_acct.branch', -1)
                ->orderBy($columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();
        }



        $columns = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => ($banks != null) ? $banks->count() : 0,
            "data" => ($banks != null) ? $banks : 0
        );

        return response()->json($columns, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!\Auth::user()->checkAccessById(27, "A"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //get input
        $bankName = $request->input('bankName');
        $bankDescription = $request->input('bankDescription');

        //create new instance
        $bank = new Bank;
        $bank->bank_code = $bankName;
        $bank->description = $bankDescription;
        $success = $bank->save();

        if($success){
            \Session::flash('success', "Bank created successfully");
            return redirect()->route('banks.index');
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('banks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!\Auth::user()->checkAccessById(27, "E"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //get input
        $bankNameEdit = $request->input('bankNameEdit');
        $bankDescriptionEdit = $request->input('bankDescriptionEdit');

        //find instance and update
        $success = Bank::where('bank_id', $id)->update([
           'bank_code' => $bankNameEdit,
           'description' => $bankDescriptionEdit
        ]);

        if($success){
            \Session::flash('success', "Bank updated successfully");
            return redirect()->route('banks.index');
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('banks.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  bank $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!\Auth::user()->checkAccessById(27, "D"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

       //find bank
        $success = Bank::where('bank_id', $id)->delete();

        if($success){
            \Session::flash('success', "Bank deleted successfully");
            return redirect()->route('banks.index');
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('banks.index');
    }
}
