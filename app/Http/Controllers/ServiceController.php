<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!\Auth::user()->checkAccess("20", "V"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //get services list
        $services = Service::orderBy('Serv_ID', 'ASC')->get();

        return view('services.index')
            ->with('services', $services);
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
        if(!\Auth::user()->checkAccess("20", "A"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //validate request
        $this->validate($request, [
            'serviceCode' => 'required',
            'serviceDescription' => 'required'
        ]);

        //if validator passed store service item
        $service = new Service;
        $service->Serv_Code = $request->input('serviceCode');
        $service->Description = $request->input('serviceDescription');
        $service->save();

        \Session::flash('success', "Service added successfully");
        return redirect()->route('services.index');
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
        if(!\Auth::user()->checkAccess("20", "E")) {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //validate request
        $this->validate($request, [
            'serviceCode' => 'required',
            'serviceDescription' => 'required'
        ]);

        //if validator passed store service item
        $service = Service::where('Serv_ID', $id)->first();
        $service->Serv_Code = $request->input('serviceCode');
        $service->Description = $request->input('serviceDescription');
        $service->save();

        \Session::flash('success', "Service updated successfully");
        return redirect()->route('services.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!\Auth::user()->checkAccess("20", "D"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //if validator passed store service item
        $service = Service::where('Serv_ID', $id)->first();
        $service->delete();

        \Session::flash('success', "Service deleted successfully");
        return redirect()->route('services.index');
    }
}
