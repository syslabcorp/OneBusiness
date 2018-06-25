<?php

namespace App\Http\Controllers;

use DB;
use App\City;
use App\Branch;
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

        return view('satelliteBranches.index')
            ->with('satelliteBranches', $satelliteBranches)
            ->with('corporations', $corporations);
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
        

        $corporations = DB::table('t_sysdata')
            ->join('corporation_masters', 't_sysdata.corp_id', '=', 'corporation_masters.corp_id')
            ->whereIn('t_sysdata.Branch', $branch)
            ->select('corporation_masters.corp_id', 'corporation_masters.corp_name')
            ->distinct()
            ->get();


        return view('satelliteBranches.create')
                ->with('corporations', $corporations);
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
        $corporations = $request->input('corporation');


        //create new instance
        $satelliteBranch = new SatelliteBranch;
        $satelliteBranch->short_name = $branchName;
        $satelliteBranch->description = $branchDescription;
        $satelliteBranch->notes = $branchNotes;
        $satelliteBranch->active = $active ? 1 : 0;
        $satelliteBranch->corp_id = $corporations != null ? $corporations : "";
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
            ->distinct()
            ->get();

        //find instance
        $satelliteBranch = SatelliteBranch::where('sat_branch', $id)->first();
        return view('satelliteBranches.edit')
            ->with('satelliteBranch', $satelliteBranch)
            ->with('corporations', $corporations);
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
        $corporations = $request->input('corporations');

        if($corporations){
            $corporations = implode(',', $corporations);
        }

        $satelliteBranch->update([
            'short_name' => $branchName,
            'description' => $branchDescription,
            'notes' => $branchNotes,
            'active' => $active ? 1 : 0,
            'corp_id' => $corporations != null ? $corporations : ""
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
        if(!\Auth::user()->checkAccessById(26, "D"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }


        $success = $satelliteBranch->delete();
        if($success){
            \Session::flash('success', "Satellite branch deleted successfully");
            return redirect()->route('satellite-branch.index');
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('satellite-branch.index');
    }

    public function getBranches(Request $request){

        $statusData = $request->input('statusData');
        $corpId = $request->input('corpId');

        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $columns = $request->input('columns');
        $orderable = $request->input('order');
        $orderNumColumn = $orderable[0]['column'];
        $orderDirection = $orderable[0]['dir'];
        $columnName = $columns[$orderNumColumn]['data'];
        $search = $request->input('search');


        $searchVal = explode(" ", $search['value']);
        $recordsTotal = SatelliteBranch::count();

        if($searchVal != null && $statusData != "" && $corpId == ""){

            $satelliteBranch = SatelliteBranch::where('active', $statusData)
                ->where(function ($q) use ($search, $columns){
                for($i = 0; $i<sizeof($columns)-1; $i++){
                    $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                }
            })
                ->orderBy($columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

            $columns = array(
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => ($satelliteBranch != null) ? $satelliteBranch->count() : 0,
                "data" => ($satelliteBranch != null) ? $satelliteBranch : 0
            );

            return response()->json($columns, 200);
        }else if($search['value'] != "" && $corpId != "" && $statusData == ""){
            $satelliteBranch = SatelliteBranch::where('corp_id', $corpId)
                ->where(function ($q) use ($search, $columns){
                    for($i = 0; $i<sizeof($columns)-1; $i++){
                        $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                    }
                })
                ->orderBy($columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();


            $columns = array(
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => ($satelliteBranch != null) ? $satelliteBranch->count() : 0,
                "data" => ($satelliteBranch != null) ? $satelliteBranch : 0
            );

            return response()->json($columns, 200);
        }else if($search['value'] != "" && $corpId != "" && $statusData != ""){
            $satelliteBranch = SatelliteBranch::where('corp_id', 'LIKE', '%'.$corpId.'%')
                ->where('active', $statusData)
                ->where(function ($q) use ($search, $columns){
                    for($i = 0; $i<sizeof($columns)-1; $i++){
                        $q->orWhere($columns[$i]['data'], 'LIKE',  '%'.$search['value'].'%');
                    }
                })
                ->orderBy($columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

            $columns = array(
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => ($satelliteBranch != null) ? $satelliteBranch->count() : 0,
                "data" => ($satelliteBranch != null) ? $satelliteBranch : 0
            );

            return response()->json($columns, 200);
        }else if($search['value'] == "" && $statusData != "" && $corpId != ""){
            $satelliteBranch = SatelliteBranch::where('corp_id', 'LIKE', '%'.$corpId.'%')
                ->where('active', $statusData)
                ->orderBy($columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

            $columns = array(
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => ($satelliteBranch != null) ? $satelliteBranch->count() : 0,
                "data" => ($satelliteBranch != null) ? $satelliteBranch : 0
            );

            return response()->json($columns, 200);
        }



        if($statusData != ""){
            $satelliteBranch = SatelliteBranch::where('active', $statusData)
                ->orderBy($columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

        }else if($corpId != ""){
            $satelliteBranch = SatelliteBranch::where('corp_id', 'LIKE', '%'.$corpId.'%')
                ->orderBy($columnName, $orderDirection)
                ->skip($start)
                ->take($length)
                ->get();

        }else{
            $satelliteBranch = null;
        }
        $columns = array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => ($satelliteBranch != null) ? $satelliteBranch->count() : 0,
            "data" => ($satelliteBranch != null) ? $satelliteBranch : 0
        );

        return response()->json($columns, 200);
    }
}
