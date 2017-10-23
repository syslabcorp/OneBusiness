<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\Checkbook;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class CheckbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if(!\Auth::user()->checkAccessById(28, "V"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }


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

        //get records from t_sysdata
        $tSysdata = DB::table('t_sysdata')
            ->orderBy('Branch', 'ASC')
            ->where('Active', 1)
            ->where('corp_id', $corporations[0]->corp_id)
            ->get();

        //get records
        $checkbooks = Checkbook::orderBy('used', 'ASC')
            ->orderBy('order_num', 'ASC')
            ->get();

        $banks = DB::table('cv_banks')
            ->join('cv_bank_acct', 'cv_banks.bank_id', '=', 'cv_bank_acct.bank_id')
            ->join('t_sysdata', 't_sysdata.Branch', '=', 'cv_bank_acct.branch')
            ->where('cv_bank_acct.corp_id', $corporations[0]->corp_id)
            ->where('cv_bank_acct.branch', $tSysdata[0]->Branch)
            ->where('t_sysdata.Active', 1)
            ->select("cv_bank_acct.bank_acct_id", DB::raw("CONCAT(cv_banks.bank_code,' - ',cv_bank_acct.acct_no) AS account_info"),
                'cv_banks.bank_code as bankNameCode', 'cv_bank_acct.bank_id AS bankId', 'cv_bank_acct.acct_no AS accountNo')
            ->orderBy('cv_banks.bank_code', 'ASC')
            ->get();




        return view('checkbooks.index')
            ->with('tSysData', $tSysdata)
            ->with('checkbooks', $checkbooks)
            ->with('branch', $branch)
            ->with('banks', $banks)
            ->with('corporations', $corporations)
            ->with('satelliteBranch', $tSysdata);
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

        if(!\Auth::user()->checkAccessById(28, "A"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //get input
        $acctId = $request->input('accountId');
        $starting = $request->input('startingNum');
        $ending = $request->input('endingNum');

        $bankCode = BankAccount::where('bank_acct_id', $acctId)->first();

        //create new instance
        $checkbook = new Checkbook;
        $checkbook->bank_acct_id = $acctId;
        $checkbook->chknum_start = $starting;
        $checkbook->chknum_end = $ending;
        $checkbook->lastchknum = $ending;
        $checkbook->bank_code = $bankCode->banks->bank_code;
        $success = $checkbook->save();

        if($success) {
            \Session::flash('alert-class', "Checkbook added successfully");
            return redirect()->route('checkbooks.index');
        }
        \Session::flash('flash_message', "Something went wrong!");
        return redirect()->route('checkbooks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Checkbook  $checkbook
     * @return \Illuminate\Http\Response
     */
    public function show(Checkbook $checkbook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Checkbook  $checkbook
     * @return \Illuminate\Http\Response
     */
    public function edit(Checkbook $checkbook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!\Auth::user()->checkAccessById(28, "E"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        $id = $request->input('accountId');
        $startNum = $request->input('editStart');
        $endNum = $request->input('editEnd');

        $update = Checkbook::where('txn_no', $id)->update([
            'chknum_start' => $startNum,
            'chknum_end' => $endNum
        ]);

        if($update){
            return response()->json("success", 200);
        }
        return response()->json("failure", 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

        if(!\Auth::user()->checkAccessById(28, "D"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        $id = $request->input('id');

        $account = Checkbook::where('txn_no', $id)->delete();

        if($account){
            return response()->json("success", 200);
        }
        return response()->json("failure", 200);
    }

    public function getAccountForCheckbook(Request $request){
        $id = $request->input('id');

        //find insance
        $bankAccount = DB::table('cv_bank_acct')
            ->join('cv_banks', 'cv_bank_acct.bank_id', '=', 'cv_banks.bank_id')
            ->select('cv_banks.bank_code as bankCode', 'cv_bank_acct.acct_no as accountNum')
            ->where('cv_bank_acct.branch', $id)
            ->get();

        return response()->json($bankAccount, 200);
    }

    public function getCheekbooks(Request $request){
        $dataStatus = $request->input('dataStatus');
        $corpID = $request->input('corpId');
        $branch = $request->input('branch');
        $mainStatus = $request->input('MainStatus');
        $sysBranch = $request->input('sysBranch');

        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $columns = $request->input('columns');
        $orderable = $request->input('order');
        $orderNumColumn = $orderable[0]['column'];
        $orderDirection = $orderable[0]['dir'];
        $columnName = $columns[$orderNumColumn]['data'];
        $search = $request->input('search');

        $recordsTotal = Checkbook::count();

        if($dataStatus != "" && $corpID != "" && $branch != "" && $sysBranch != "" && $mainStatus == "false" && $search['value'] == ""){
            //get records
            $checkbooks = DB::table('cv_chkbk_series')
                ->join('cv_bank_acct', 'cv_chkbk_series.bank_acct_id', '=', 'cv_bank_acct.bank_acct_id')
                ->join('t_sysdata', 'cv_bank_acct.branch', '=', 't_sysdata.Branch')
                ->where('t_sysdata.Active', $dataStatus)
                ->where('cv_bank_acct.corp_id', $corpID)
                ->where('cv_bank_acct.branch', $sysBranch)
                ->where('cv_bank_acct.bank_acct_id', $branch)
                ->select('cv_bank_acct.bank_acct_id', 'cv_bank_acct.branch', 'cv_bank_acct.bank_id', 'cv_bank_acct.acct_no', 'cv_bank_acct.default_acct',
                    'cv_bank_acct.corp_id', 'cv_chkbk_series.txn_no', 'cv_chkbk_series.bank_acct_id', 'cv_chkbk_series.order_num', 'cv_chkbk_series.chknum_start',
                    'cv_chkbk_series.chknum_end', 'cv_chkbk_series.lastchknum', 'cv_chkbk_series.used', 'cv_chkbk_series.bank_code', 't_sysdata.ShortName')
                ->orderBy('cv_chkbk_series.used', 'ASC')
                ->orderBy('cv_chkbk_series.order_num', 'ASC')
                ->orderBy('cv_chkbk_series.'.$columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

            $pagination = DB::table('cv_chkbk_series')
                ->join('cv_bank_acct', 'cv_chkbk_series.bank_acct_id', '=', 'cv_bank_acct.bank_acct_id')
                ->join('t_sysdata', 'cv_bank_acct.branch', '=', 't_sysdata.Branch')
                ->where('t_sysdata.Active', $dataStatus)
                ->where('cv_bank_acct.corp_id', $corpID)
                ->where('cv_bank_acct.branch', $sysBranch)
                ->where('cv_bank_acct.bank_acct_id', $branch)
                ->count();


        }else if($mainStatus == "true" && $search['value'] == ""){
            //get records
            $checkbooks = DB::table('cv_chkbk_series')
                ->join('cv_bank_acct', 'cv_chkbk_series.bank_acct_id', '=', 'cv_bank_acct.bank_acct_id')
                ->orderBy('cv_chkbk_series.used', 'ASC')
                ->orderBy('cv_chkbk_series.order_num', 'ASC')
                ->where('cv_bank_acct.branch', -1)
                ->orderBy('cv_chkbk_series.'.$columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

            $pagination = DB::table('cv_chkbk_series')
                ->join('cv_bank_acct', 'cv_chkbk_series.bank_acct_id', '=', 'cv_bank_acct.bank_acct_id')
                ->where('cv_bank_acct.branch', -1)
                ->count();
        }else if($dataStatus != "" && $corpID != "" && $branch != "" && $sysBranch != "" && $mainStatus == "false" && $search['value'] != ""){
            //get records
            $checkbooks = DB::table('cv_chkbk_series')
                ->join('cv_bank_acct', 'cv_chkbk_series.bank_acct_id', '=', 'cv_bank_acct.bank_acct_id')
                ->join('t_sysdata', 'cv_bank_acct.branch', '=', 't_sysdata.Branch')
                ->where(function ($q) use ($search, $columns){
                    for($i = 0;  $i<2; $i++){
                        $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                    }
                })
                ->where('t_sysdata.Active', $dataStatus)
                ->where('cv_bank_acct.corp_id', $corpID)
                ->where('cv_bank_acct.bank_acct_id', $branch)
                ->where('cv_bank_acct.Branch', $sysBranch)
                ->select('cv_bank_acct.bank_acct_id', 'cv_bank_acct.branch', 'cv_bank_acct.bank_id', 'cv_bank_acct.acct_no', 'cv_bank_acct.default_acct',
                    'cv_bank_acct.corp_id', 'cv_chkbk_series.txn_no', 'cv_chkbk_series.bank_acct_id', 'cv_chkbk_series.order_num', 'cv_chkbk_series.chknum_start',
                    'cv_chkbk_series.chknum_end', 'cv_chkbk_series.lastchknum', 'cv_chkbk_series.used', 'cv_chkbk_series.bank_code', 't_sysdata.ShortName')
                ->orderBy('cv_chkbk_series.used', 'ASC')
                ->orderBy('cv_chkbk_series.order_num', 'ASC')
                ->orderBy('cv_chkbk_series.'.$columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

            $pagination = DB::table('cv_chkbk_series')
                ->join('cv_bank_acct', 'cv_chkbk_series.bank_acct_id', '=', 'cv_bank_acct.bank_acct_id')
                ->join('pc_branches', 'cv_bank_acct.branch', '=', 'pc_branches.sat_branch')
                ->where(function ($q) use ($search, $columns){
                    for($i = 0; $i<2; $i++){
                        $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                    }
                })
                ->where('pc_branches.active', $dataStatus)
                ->where('cv_bank_acct.Branch', $sysBranch)
                ->where('cv_bank_acct.corp_id', $corpID)
                ->where('cv_bank_acct.bank_acct_id', $branch)
                ->count();
        }else if($mainStatus == "true" && $search['value'] != "")
        {
            //get records
            $checkbooks = DB::table('cv_chkbk_series')
                ->join('cv_bank_acct', 'cv_chkbk_series.bank_acct_id', '=', 'cv_bank_acct.bank_acct_id')
                ->leftjoin('t_sysdata', 'cv_bank_acct.Branch', '=', 't_sysdata.Branch')
                ->where(function ($q) use ($search, $columns){
                    for($i = 0; $i<2; $i++){
                        $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                    }
                })
                ->where('cv_bank_acct.branch', -1)
                ->select('cv_bank_acct.bank_acct_id', 'cv_bank_acct.branch', 'cv_bank_acct.bank_id', 'cv_bank_acct.acct_no', 'cv_bank_acct.default_acct',
                    'cv_bank_acct.corp_id', 'cv_chkbk_series.txn_no', 'cv_chkbk_series.bank_acct_id', 'cv_chkbk_series.order_num', 'cv_chkbk_series.chknum_start',
                    'cv_chkbk_series.chknum_end', 'cv_chkbk_series.lastchknum', 'cv_chkbk_series.used', 'cv_chkbk_series.bank_code', 't_sysdata.ShortName')
                ->orderBy('cv_chkbk_series.used', 'ASC')
                ->orderBy('cv_chkbk_series.order_num', 'ASC')
                ->orderBy('cv_chkbk_series.'.$columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

            $pagination = DB::table('cv_chkbk_series')
                ->join('cv_bank_acct', 'cv_chkbk_series.bank_acct_id', '=', 'cv_bank_acct.bank_acct_id')
                ->leftjoin('t_sysdata', 'cv_bank_acct.Branch', '=', 't_sysdata.Branch')
                ->where(function ($q) use ($search, $columns){
                    for($i = 0; $i<2; $i++){
                        $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                    }
                })
                ->where('cv_bank_acct.branch', -1)
                ->count();
        }


        $columns = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => isset($pagination) && ($pagination != "") ? $pagination : 0,
            "data" => isset($checkbooks) && ($checkbooks != "") ? $checkbooks : ''
        );

        return response()->json($columns, 200);
    }

    public function editRowOrder(Request $request){
        $rowId = $request->input('rowId');
        $rowId2 = $request->input('rowId2');
        $row1 = $request->input('order_num');
        $row2 = $request->input('order_num2');

        DB::table('cv_chkbk_series')->where('txn_no', $rowId)->update(['order_num' => $row1]);
        DB::table('cv_chkbk_series')->where('txn_no', $rowId2)->update(['order_num' => $row2]);


        return response()->json("success", 200);
    }

    /**
     * Return branches for corporation and status
     * @param Request $request
     * @return collection of branches in json format
     */
    public function getBranches(Request $request){
        $corpId  = $request->input('corpId');
        $status  = $request->input('status');

        //get records from t_sysdata
        $tSysdata = DB::table('t_sysdata')
            ->orderBy('Branch', 'ASC')
            ->where('Active', intval($status))
            ->where('corp_id', intval($corpId))
            ->select('Branch', 'ShortName')
            ->get();

        return response()->json(json_encode($tSysdata), 200);
    }

    /**
     * @param Request $request
     * @return collection of banks with accounts
     * for a specific branch
     */
    public function getBanks(Request $request){

        $branchId = $request->input('branchId');

        $banks = DB::table('cv_banks')
            ->join('cv_bank_acct', 'cv_banks.bank_id', '=', 'cv_bank_acct.bank_id')
            ->where('cv_bank_acct.branch', $branchId)
            ->select("cv_bank_acct.bank_acct_id", DB::raw("CONCAT(cv_banks.bank_code,' - ',cv_bank_acct.acct_no) AS account_info"),
                'cv_banks.bank_code as bankNameCode', 'cv_bank_acct.bank_id AS bankId', 'cv_bank_acct.acct_no AS accountNo')
            ->orderBy('cv_banks.bank_code', 'ASC')
            ->get();

        return response()->json(json_encode($banks), 200);
    }
}
