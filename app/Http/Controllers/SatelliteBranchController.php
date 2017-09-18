<?php

namespace App\Http\Controllers;

use App\SatelliteBranch;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SatelliteBranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!\Auth::user()->checkAccessById(26, "V"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //get all items
        $satelliteBranches = SatelliteBranch::orderBy('sat_branch', 'DESC')->get();

        return view('satelliteBranches.index')
            ->with('satelliteBranches', $satelliteBranches);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!\Auth::user()->checkAccessById(26, "A"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        return view('satelliteBranches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!\Auth::user()->checkAccessById(26, "A"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //get input
        $branchName = $request->input('branchName');
        $branchDescription = $request->input('branchDescription');
        $branchNotes = $request->input('branchNotes');
        $active = $request->input('itemActive');

        //create new instance
        $satelliteBranch = new SatelliteBranch;
        $satelliteBranch->short_name = $branchName;
        $satelliteBranch->description = $branchDescription;
        $satelliteBranch->notes = $branchNotes;
        $satelliteBranch->active = $active ? 1 : 0;
        $success = $satelliteBranch->save();

        if($success) {
            \Session::flash('success', "Item added successfully");
            return redirect()->route('satellite-branch.index');
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('satellite-branch.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SatelliteBranch  $satelliteBranch
     * @return \Illuminate\Http\Response
     */
    public function show(SatelliteBranch $satelliteBranch)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id) //SatelliteBranch $satelliteBranch
    {
        if(!\Auth::user()->checkAccessById(26, "E"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //find instance
        $satelliteBranch = SatelliteBranch::where('sat_branch', $id)->first();
        return view('satelliteBranches.edit')
            ->with('satelliteBranch', $satelliteBranch);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SatelliteBranch  $satelliteBranch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SatelliteBranch $satelliteBranch)
    {
        if(!\Auth::user()->checkAccessById(26, "E"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }

        //get input
        $branchName = $request->input('branchName');
        $branchDescription = $request->input('branchDescription');
        $branchNotes = $request->input('branchNotes');
        $active = $request->input('itemActive');

        $satelliteBranch->update([
           'short_name' => $branchName,
            'description' => $branchDescription,
            'notes' => $branchNotes,
            'active' => $active ? 1 : 0
        ]);

        \Session::flash('success', "Satellite branch updated successfully");
        return redirect()->route('satellite-branch.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SatelliteBranch  $satelliteBranch
     * @return \Illuminate\Http\Response
     */
    public function destroy(SatelliteBranch $satelliteBranch)
    {
        //
    }
}
