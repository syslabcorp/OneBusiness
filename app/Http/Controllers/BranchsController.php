<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;

class BranchsController extends Controller
{
    public function index(Request $request)
    {

        if(!\Auth::user()->checkAccess("Branch Setup & Details", "V"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $status = !empty($request->get('status')) ? $request->get('status') : "active";

        $branchs = Branch::orderBy('updated_at', 'DESC');

        if($status == "active") {
            $branchs = $branchs->where('active', '=', 1);
        }elseif($status == "inactive") {
            $branchs = $branchs->where('active', '!=', 1);
        }
        $branchs = $branchs->get();

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
            'branchs' => $result,
            'status' => $status
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
            'Prov_ID' => 'required',
            'City_ID' => 'required',
            'units' => 'required|numeric'
        ]);
        $params = $request->all();
        $params['Street'] = $params['street'];
        $params['Active'] = isset($params['active']) ? 1 : 0;
        $params['Branch'] = $params['branch_name'];
        $params['Description'] = $params['operator'];
        $params['City_ID'] = $params['City_ID'];
        $params['MaxUnits'] = $params['units'];

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
            'branchs' => Branch::orderBy('Branch', 'ASC')->get()
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
        $params['Street'] = $params['street'];
        $params['Active'] = isset($params['active']) ? 1 : 0;
        $params['Branch'] = $params['branch_name'];
        $params['Description'] = $params['operator'];
        $params['City_ID'] = $params['city'];
        $params['MaxUnits'] = $params['units'];

        $branch->macs()->delete();
        for($i = 0; $i < $params['units']; $i++)
        {
            $branch->macs()->create(['pc_no' => $i + 1]);
        }

        \Session::flash('success', "Branch {$branch->Branch} has been updated!");

        $branch->update($params);

        return redirect(route('branchs.edit', [$branch, '#branch-details']));
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

        $params = $request->only('StubHdr', 'StubMsg', 'MAC_Address', 'cashier_ip',
            'RollOver', 'TxfrRollOver', 'PostPtrPort', 'susp_ping_timeout', 'max_eload_amt',
            'lc_uid', 'lc_pwd', 'is_enable_printing');

        $params['to_mobile_num'] = $request->get('receiving_mobile_number');

        $branch->update($params);
        \Session::flash('success', "Branch {$branch->Branch} has been updated!");
        return redirect(route('branchs.edit', [$branch]));
    }
}
