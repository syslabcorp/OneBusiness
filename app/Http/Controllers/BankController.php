<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankAccount;
use App\SatelliteBranch;
use App\City;
use App\Branch;
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



        //get user data
        // $branches = DB::table('user_area')
        //     ->where('user_ID', '=', \Auth::user()->UserID)
        //     ->pluck('branch');

        // $branch = explode(",", $branches[0]);

        if((\Auth::user()->area))
        {
          if((\Auth::user()->area->branch))
          {
            $branch = explode( ',' ,\Auth::user()->area->branch );
          }

          if((\Auth::user()->area->province))
          {
            $provinces_ID = explode( ',' ,\Auth::user()->area->province );
            $cities = City::WhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();

            $cities_ID = $cities->map(function($item) {
              return $item['City_ID'];
            });

            $branchs_list = Branch::whereIn('City_ID', $cities_ID)->get();

            $branchs_ID = $branchs_list->map(function($item) {
              return $item['Branch'];
            });

            $branch = $branchs_ID->toArray();
          }

          if((\Auth::user()->area->city))
          {
            $cities_ID = explode( ',' ,\Auth::user()->area->city );
            $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();

            $branchs_list = Branch::whereIn('City_ID', $cities_ID)->get();

            $branchs_ID = $branchs_list->map(function($item) {
              return $item['Branch'];
            });

            $branch = $branchs_ID->toArray();
          }
        }
        else
        {
            $branch = [];
        }


        //dd($branch);
        $corporations = DB::table('t_sysdata')
            ->join('corporation_masters', 't_sysdata.corp_id', '=', 'corporation_masters.corp_id')
            ->whereIn('t_sysdata.Branch', $branch)
            ->select('corporation_masters.corp_id', 'corporation_masters.corp_name')
            ->orderBy('corporation_masters.corp_name', 'ASC')
            ->distinct()
            ->get();

            
        if(isset($corporations[0]->corp_id)) {

            //get records from t_sysdata
            $tSysdata = DB::table('t_sysdata')
                ->orderBy('ShortName', 'ASC')
                ->where('Active', 1)
                ->where('corp_id', $corporations[0]->corp_id)
                ->select('t_sysdata.ShortName', 't_sysdata.Active', 't_sysdata.Branch', 't_sysdata.corp_id')
                ->get();
        }else{
            $tSysdata[] = null;
        }
        
        

        //get banks from db
        $selectBank = Bank::orderBy('bank_id', 'ASC')->get();
        $selectCorp = DB::table('corporation_masters')->orderBy('corp_name', 'ASC')->get();


        return view('banks.index')
            ->with('selectBank', $selectBank)
            ->with('branch', $branch)
            ->with('corporations', $corporations)
            ->with('selectCorp', $selectCorp)
            ->with('satelliteBranch', $tSysdata);
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


        $banks = "";
        $recordsTotal = BankAccount::count();

        if($statusData != "" && $branch != "" && $corpID != "" && $mainStatus == "false"){
            $banks = DB::table('cv_bank_acct')
                ->join('cv_banks', 'cv_bank_acct.bank_id', '=', 'cv_banks.bank_id')
                ->join('t_sysdata', 'cv_bank_acct.branch', '=', 't_sysdata.Branch')
                ->where('cv_bank_acct.Branch', $branch)
                ->where('t_sysdata.Active', $statusData)
                ->where('t_sysdata.corp_id', $corpID)
                ->select('cv_bank_acct.default_acct', 'cv_bank_acct.date_created', 'cv_banks.bank_code', 'cv_bank_acct.acct_no', 'cv_bank_acct.branch',
                    'cv_bank_acct.corp_id', 'cv_bank_acct.bank_acct_id', 'cv_bank_acct.bank_id')
                ->orderBy($columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

        }else if($mainStatus != "false"){
            $banks = DB::table('cv_bank_acct')
                ->join('cv_banks', 'cv_bank_acct.bank_id', '=', 'cv_banks.bank_id')
                ->where('cv_bank_acct.branch', -1)
                ->select('cv_bank_acct.default_acct', 'cv_bank_acct.date_created', 'cv_banks.bank_code', 'cv_bank_acct.acct_no', 'cv_bank_acct.branch',
                    'cv_bank_acct.corp_id', 'cv_bank_acct.bank_acct_id', 'cv_bank_acct.bank_id')
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
}
