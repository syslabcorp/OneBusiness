<?php

namespace App\Http\Controllers;

use App\Bank;
use App\BankAccount;
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

        $banks = BankAccount::orderBy('bank_id', 'ASC')->get();
        //get banks from db
        $selectBank = Bank::orderBy('bank_id', 'ASC')->get();

        return view('banks.index')
            ->with('selectBank', $selectBank)
            ->with('banks', $banks);
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
