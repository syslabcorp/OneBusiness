<?php

namespace App\Http\Controllers;

use DB;
use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*if(!\Auth::user()->checkAccessById(29, "V"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }*/

        $corporations = DB::table('corporation_masters')
            ->orderBy('corp_name', 'ASC')
            ->get();


        $vendors = Vendor::orderBy('VendorName', 'ASC')->get();
        $corpName = $corporations[0]->corp_name;



        return view('vendors.index')
            ->with('vendors', $vendors)
            ->with('corpName', $corpName)
            ->with('corporations', $corporations);
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
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Vendor $vendor,Request $request)
    {
     
        
        if(!\Auth::user()->checkAccessById(29, "V"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        /*   $vendors = new Vendor();
       $vendors->setConnection('openbus');*/
        //get records

        //$url = $request->input('corp');
        //$url != null ? session(['corpUrl' => $url]) : null;

        $corporations = DB::table('corporation_masters')
            ->orderBy('corp_name', 'ASC')
            ->get();
            
        if( isset($request->main) && $request->main == "true" )
        {
        $vendors = DB::table('cv_vendacct')
            ->join('s_vendors', 'cv_vendacct.supp_id', '=', 's_vendors.Supp_ID')
            ->join('corporation_masters', 'cv_vendacct.corp_id', '=', 'corporation_masters.corp_id')
            ->where('cv_vendacct.nx_branch', -1)
            ->where('s_vendors.Supp_ID', $vendor->Supp_ID)
            ->orderBy('VendorName', 'ASC')
            ->get();
        }
        else
        {
        $vendors = DB::table('cv_vendacct')
            ->join('s_vendors', 'cv_vendacct.supp_id', '=', 's_vendors.Supp_ID')
            ->join('corporation_masters', 'cv_vendacct.corp_id', '=', 'corporation_masters.corp_id')
            ->join('t_sysdata', 'cv_vendacct.nx_branch', '=', 't_sysdata.Branch')
            ->where('s_vendors.Supp_ID', $vendor->Supp_ID)
            ->orderBy('VendorName', 'ASC')
            ->get();
        }

      /*  if($url == null)
        {



            session(['corpUrl' => $vendors[0]->corp_id]);
        }else{
            $vendors = DB::table('cv_vendacct')
                ->join('s_vendors', 'cv_vendacct.supp_id', '=', 's_vendors.Supp_ID')
                ->join('corporation_masters', 'cv_vendacct.corp_id', '=', 'corporation_masters.corp_id')
                ->join('t_sysdata', 'cv_vendacct.nx_branch', '=', 't_sysdata.Branch')
                ->where('s_vendors.Supp_ID', $vendor->Supp_ID)
                ->where('cv_vendacct.corp_id', $url)
                ->orderBy('VendorName', 'ASC')
                ->get();
        }*/

        $branches = DB::table('t_sysdata')
            ->orderBy('Description', 'ASC')
            ->get();

        // return response()->json($vendors, 200);
        
        return view('vendors.management.index')
            ->with('vendors', $vendors)
            ->with('corporations', $corporations)
            ->with('branches', $branches)
            ->with('main', $request->main);
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
            \Session::flash('success', "Vendor updated successfully");
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

    /**
     * Return branches for corporation and status
     * @param Request $request
     * @return collection of branches in json format
     */
    public function getBranches(Request $request){
        $corpId  = $request->input('corpId');

        //get records from t_sysdata
        $tSysdata = DB::table('t_sysdata')
            ->orderBy('Branch', 'ASC')
            ->where('corp_id', intval($corpId))
            ->where('Active', 1)
            ->select('Branch', 'ShortName')
            ->get();

        return response()->json(json_encode($tSysdata), 200);
    }
}
