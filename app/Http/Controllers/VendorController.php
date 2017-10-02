<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!\Auth::user()->checkAccessById(29, "V"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }
        //get records
        $vendors = Vendor::orderBy('VendorName', 'ASC')->get();

        return view('vendors.index')->with('vendors', $vendors);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!\Auth::user()->checkAccessById(29, "A"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        return view('vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!\Auth::user()->checkAccessById(29, "A"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        $vendorName = $request->input('vendorName');
        $payTo = $request->input('payTo');
        $address = $request->input('address');
        $contactPerson = $request->input('contactPerson');
        $telNo1 = $request->input('telNo1');
        $telNo2 = $request->input('telNo2');
        $cellPhone = $request->input('cellphone');
        $payeesAccountOnly = $request->input('payeesAccountOnly');
        $usageTracking = $request->input('usageTracking');
        $visibilityInfo = $request->input('visibilityInfo');

        //create new instance
        $vendor = new Vendor;
        $vendor->VendorName = $vendorName;
        $vendor->PayTo = $payTo;
        $vendor->Address = $address;
        $vendor->ContactPerson = $contactPerson;
        $vendor->TelNo = $telNo1;
        $vendor->OfficeNo = $telNo2;
        $vendor->CelNo = $cellPhone;
        $vendor->x_check = $payeesAccountOnly ? 1 : 0;
        $vendor->withTracking = $usageTracking ? 1 : 0;
        $vendor->petty_visible = $visibilityInfo;
        $success = $vendor->save();

        if($success){
            \Session::flash('success', "Vendor created successfully");
            return redirect()->route('vendors.index');
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('vendors.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit(Vendor $vendor)
    {
        if(!\Auth::user()->checkAccessById(29, "E"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        return view('vendors.edit', ['vendor' => $vendor]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vendor $vendor)
    {
        if(!\Auth::user()->checkAccessById(29, "E"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        $vendorName = $request->input('vendorName');
        $payTo = $request->input('payTo');
        $address = $request->input('address');
        $contactPerson = $request->input('contactPerson');
        $telNo1 = $request->input('telNo1');
        $telNo2 = $request->input('telNo2');
        $cellPhone = $request->input('cellphone');
        $payeesAccountOnly = $request->input('payeesAccountOnly');
        $usageTracking = $request->input('usageTracking');
        $visibilityInfo = $request->input('visibilityInfo');

        //create new instance
        $success = $vendor->update([
            'VendorName' => $vendorName,
            'PayTo' => $payTo,
            'Address' => $address,
            'ContactPerson' => $contactPerson,
            'TelNo' => $telNo1,
            'OfficeNo' => $telNo2,
            'CelNo' => $cellPhone,
            'x_check' => $payeesAccountOnly ? 1 : 0,
            'withTracking' => $usageTracking ? 1 : 0,
            'petty_visible' => $visibilityInfo
        ]);

        if($success){
            \Session::flash('success', "Vendor created successfully");
            return redirect()->route('vendors.index');
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('vendors.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        if(!\Auth::user()->checkAccessById(29, "D"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }


        $success = $vendor->delete();
        if($success){
            \Session::flash('success', "Vendor deleted successfully");
            return redirect()->route('vendors.index');
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('vendors.index');
    }
}
