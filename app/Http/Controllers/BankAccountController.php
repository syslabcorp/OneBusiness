<?php

namespace App\Http\Controllers;

use App\BankAccount;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }
        //get input
        $bankID = $request->input('bankCode');
        $bankAccountNumber = $request->input('bankAccountNumber');
        $pcBranch = $request->input('pcBranchId');


        //create new instance
        $account = new BankAccount;
        $account->bank_id = $bankID;
        $account->acct_no = $bankAccountNumber;
        $account->branch = $pcBranch;
        $account->date_created = \Carbon\Carbon::now();
        $success = $account->save();

        if($success){
            \Session::flash('alert-class', "Bank Account added successfully");
            return redirect()->route('banks.index');
        }
        \Session::flash('flash_message', "Something went wrong!");
        return redirect()->route('banks.index');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!\Auth::user()->checkAccessById(27, "E")) {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //get input
        $bankId = $request->input('bankAccountCodeEdit');
        $accountNum = $request->input('bankAccountNumberEdit');
        $accountId = $request->input('accountID');

        //find instance and update
        $success  = BankAccount::where('bank_acct_id', $accountId)->update([
            'bank_id' => $bankId,
            'acct_no' => $accountNum
        ]);

        if($success){
            \Session::flash('alert-class', "Account updated successfully");
            return response()->json(200);
        }
        \Session::flash('flash_message', "Something went wrong!");
        return redirect()->route('banks.index');
    }


    public function updateAccount(Request $request)
    {
        if(!\Auth::user()->checkAccessById(27, "E")) {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //get input
        $bankId = $request->input('bankAccountCodeEdit');
        $accountNum = $request->input('bankAccountNumberEdit');
        $accountId = $request->input('accountID');

        //find instance and update
        $accountUpdate = BankAccount::where('bank_acct_id', $accountId)->first();
        $accountUpdate->bank_id = $bankId;
        $accountUpdate->acct_no = $accountNum;
        $success = $accountUpdate->save();

        if($success){
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
        if(!\Auth::user()->checkAccessById(27, "D"))
        {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        $account = BankAccount::where('bank_acct_id', $request->input('id'))->delete();

        if($account){
            return response()->json("success", 200);
        }
        return response()->json("failure", 200);
    }

    public function changeDefaultAccount(Request $request)
    {
        if(!\Auth::user()->checkAccessById(27, "E")) {
            \Session::flash('flash_message', "You don't have permission");
            return redirect("/home");
        }

        //get instance
        $id = $request->input('id');

        //set all default acct fields to 0
        BankAccount::where('default_acct', 1)->update(['default_acct' => 0]);
        //change default account
        $success = BankAccount::where('bank_acct_id', $id)->update([
            'default_acct' => 1
        ]);

        if($success){
            return response()->json('success', 200);
        }
    }
}
