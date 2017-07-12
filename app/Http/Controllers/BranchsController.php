<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;

class BranchsController extends Controller
{
    public function index()
    {

        if(!\Auth::user()->checkAccess("Branch Setup & Details", "V"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $branchs = Branch::orderBy('updated_at', 'DESC')->get();

        $result = [];
        foreach($branchs as $branch)
        {
            if(!isset($result[$branch->city->province->id]['count']))
            {
                $result[$branch->city->province->id]['count'] = 0;
            }
            $result[$branch->city->province->id]['cities'][$branch->city_id][] = $branch;
            $result[$branch->city->province->id]['count'] += 1;
        }
        return view('branchs.index', [
            'branchs' => $result
        ]);
    }

    public function create()
    {
        if(!\Auth::user()->checkAccess("Branch Setup & Details", "A"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        return view('branchs.create');
    }

    public function store(Request $request)
    {
        if(!\Auth::user()->checkAccess("Branch Setup & Details", "A"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $this->validate($request,[
            'branch_name' => 'required',
            'operator' => 'required',
            'street' => 'required',
            'province' => 'required',
            'city' => 'required',
            'units' => 'required|numeric'
        ]);
        $params = $request->all();
        $params['description'] = $params['operator'];
        $params['city_id'] = $params['city'];
        $params['max_units'] = $params['units'];

        $branch = Branch::create($params);
        for($i = 0; $i < $params['units']; $i++)
        {
            $branch->macs()->create(['pc_no' => $i + 1]);
        }

        \Session::flash('success', "New branch has been created.");

        return redirect(route('branchs.index'));
    }

    public function edit(Branch $branch)
    {

        return view('branchs.edit', [
            'branch' => $branch,
            'branchs' => Branch::orderBy('branch_name', 'ASC')->get()
        ]);
    }

    public function update(Request $request, Branch $branch)
    {

        if(!\Auth::user()->checkAccess("Branch Setup & Details", "E"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $this->validate($request,[
            'branch_name' => 'required',
            'operator' => 'required',
            'street' => 'required',
            'province' => 'required',
            'city' => 'required',
            'units' => 'required|numeric'
        ]);

        $params = $request->all();
        $params['description'] = $params['operator'];
        $params['city_id'] = $params['city'];
        $params['max_units'] = $params['units'];
        $params['active'] = empty($params['active']) ? "0" : "1";

        $branch->macs()->delete();
        for($i = 0; $i < $params['units']; $i++)
        {
            $branch->macs()->create(['pc_no' => $i + 1]);
        }

        \Session::flash('success', "Branch {$branch->branch_name} has been updated!");

        $branch->update($params);

        return redirect(route('branchs.index'));
    }

    public function updateMisc(Request $request, Branch $branch)
    {
        if(!\Auth::user()->checkAccess("Miscellaneous Settings", "E"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect(route('branchs.index')); 
        }

        $this->validate($request, [
            'receiving_mobile_number' => 'max:11'
        ]);

        $params = $request->only('stub_hdr', 'stub_msg', 'mac_address', 'cashier_ip',
            'roll_over', 'txfr_roll_over', 'pos_ptr_port', 'susp_ping_timeout', 'max_eload_amt',
            'lc_uid', 'lc_pwd', 'is_enable_printing');

        $params['to_mobile_num'] = $request->get('receiving_mobile_number');

        $branch->update($params);
        \Session::flash('success', "Branch {$branch->branch_name} has been updated!");
        return redirect(route('branchs.edit', [$branch]));
    }
}
