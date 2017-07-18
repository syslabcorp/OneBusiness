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

        $branchs = Branch::orderBy('Branch', 'ASC');

        if($status == "active") {
            $branchs = $branchs->where('active', '=', 1);
        }elseif($status == "inactive") {
            $branchs = $branchs->where('active', '!=', 1);
        }
        $branchs = $branchs->get();

        $result = [];
        foreach($branchs as $branch)
        {
            if(!isset($result[$branch->city->province->Prov_ID]['count']))
            {
                $result[$branch->city->province->Prov_ID]['count'] = 0;
            }
            $result[$branch->city->province->Prov_ID]['cities'][$branch->City_ID][] = $branch;
            $result[$branch->city->province->Prov_ID]['count'] += 1;
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
        $params['ShortName'] = $params['branch_name'];
        $params['Description'] = $params['operator'];
        $params['City_ID'] = $params['City_ID'];
        $params['MaxUnits'] = $params['units'];
        $params['StubPrint'] = 0;

        $branch = Branch::create($params);
        for($i = 0; $i < $params['units']; $i++)
        {
            $branch->macs()->create(['PC_No' => $i + 1]);
        }

        $services = \DB::table('services')->get();
        foreach($services as $service){
            \DB::table('srv_item_cfg')->insert([
                'Serv_ID' => $service->Serv_ID,
                'Active' => 0,
                'Branch' => $branch->Branch
            ]);
        }

        $invtries = \DB::table('s_invtry_hdr')->get();
        foreach($invtries as $invtry) {
            \DB::table('s_item_cfg')->insert([
                'item_id' => $invtry->item_id,
                'Active' => 0,
                'ItemCode' => $invtry->ItemCode,
                'Branch' => $branch->Branch
            ]);
        }

        \DB::table('s_changes')->insert([
            'invtry_hdr' => 1,
            'prodline' => 1,
            'brands' => 1,
            'item_cfg' => 1,
            'Branch' => $branch->Branch
        ]);

        $branch->update(['Modified' => 1]);

        \Session::flash('success', "New branch has been created.");

        return redirect(route('branchs.index'));
    }

    public function edit(Branch $branch)
    {
        if(!\Auth::user()->checkAccess("Branch Setup & Details", "E")) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home");
        }


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
        $params['ShortName'] = $params['branch_name'];
        $params['Description'] = $params['operator'];
        $params['City_ID'] = $params['city'];
        $params['MaxUnits'] = $params['units'];

        if($branch->MaxUnits > $params['MaxUnits']) {
            for($i = 0; $i < $branch->MaxUnits - $params['MaxUnits']; $i++)
            {
                $branch->macs()->orderBy("nKey", "DESC")->first()->delete();
            }
        }else if($branch->MaxUnits < $params['MaxUnits']) {
            for($i = 0; $i < $params['MaxUnits'] - $branch->MaxUnits; $i++)
            {
                $branch->macs()->create(['PC_No' => $branch->MaxUnits + $i]);
            }
        }
        

        \Session::flash('success', "Branch {$branch->ShortName} has been updated!");

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
            'receiving_mobile_number' => 'max:11',
            'cashier_ip' => 'required',
            'MAC_Address' => 'required'
        ]);

        $params = $request->only('StubHdr', 'StubMsg', 'MAC_Address', 'cashier_ip',
            'RollOver', 'TxfrRollOver', 'PosPtrPort', 'susp_ping_timeout', 'max_eload_amt',
            'lc_uid', 'lc_pwd', 'StubPrint');

        $params['to_mobile_num'] = $request->get('receiving_mobile_number');
        $params['StubPrint'] = empty($params['StubPrint']) ? 0 : 1;

        $branch->update($params);
        \Session::flash('success', "Branch {$branch->ShortName} has been updated!");
        return redirect(route('branchs.edit', [$branch]));
    }
}
