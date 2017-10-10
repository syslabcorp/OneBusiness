<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;

class BranchsController extends Controller
{
    public function index(Request $request)
    {
        if(!\Auth::user()->checkAccessById(1, "V"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $status = !empty($request->get('status')) ? $request->get('status') : "active";

        $branchs = Branch::orderBy('Branch', 'ASC');

        $branchIds = [];
        $cityIds = [];
        $provinceIds = [];

        if(\Auth::user()->area) {
          $branchIds = explode(",", \Auth::user()->area->branch);
          $cityIds = explode(",", \Auth::user()->area->city);
          $provinceIds = explode(",", \Auth::user()->area->province);
        }

        $branchs = $branchs->orWhereIn('Branch', $branchIds)
                           ->orWhereIn('t_sysdata.City_ID', $cityIds)
                           ->leftJoin("t_cities", "t_cities.City_ID", "=", "t_sysdata.City_ID")
                           ->orWhereIn('t_cities.Prov_ID', $provinceIds);

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
            'status' => $status,
            'corpId' => $request->get('corpID')
        ]);
    }

    public function create(Request $request)
    {
        if(!\Auth::user()->checkAccessById(1, "A"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        return view('branchs.create', ['corpId' => $request->get('corpID')]);
    }

    public function store(Request $request)
    {
        if(!\Auth::user()->checkAccessById(1, "A"))
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
        $params['corp_id'] = isset($params['corpID']) ? $params['corpID'] : null;

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
        if(!\Auth::user()->checkAccessById(1, "E")) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home");
        }


        $lcUid = Branch::select(\DB::raw("AES_DECRYPT(lc_uid, '" . env("LOADCENTRAL_PWDKEY") .  "') as lc_uid"))->where("Branch", "=", $branch->Branch)->first();

        return view('branchs.edit', [
            'branch' => $branch,
            'branchs' => Branch::orderBy('Branch', 'ASC')->get(),
            'lc_uid' => $lcUid->lc_uid
        ]);
    }

    public function update(Request $request, Branch $branch)
    {

        if(!\Auth::user()->checkAccessById(1, "E"))
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
            $last = $branch->macs()->orderBy("nKey", "DESC")->first()->nKey + 1;

            for($i = 0; $i < $params['MaxUnits'] - $branch->MaxUnits; $i++)
            {
                $branch->macs()->create(['PC_No' => $last + $i]);
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
            'MAC_Address' => 'required|unique:mysql2.t_rates,Mac_Address,*,nKey|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/',
            'cashier_ip' => 'required|regex:/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/'
        ]);

        $params = $request->only('StubHdr', 'StubMsg', 'MAC_Address', 'cashier_ip',
            'RollOver', 'TxfrRollOver', 'PosPtrPort', 'susp_ping_timeout', 'max_eload_amt',
            'lc_uid', 'lc_pwd', 'StubPrint');

        $params['to_mobile_num'] = $request->get('receiving_mobile_number');
        $params['StubPrint'] = empty($params['StubPrint']) ? 0 : 1;
        if(!empty($params['lc_pwd'])) {
            $params['lc_pwd'] = \DB::raw("AES_ENCRYPT('{$params['lc_pwd']}', '" . env("LOADCENTRAL_PWDKEY") .  "')");
        } else {
            unset($params['lc_pwd']);
        }

        $params['lc_uid'] = \DB::raw("AES_ENCRYPT('{$params['lc_uid']}', '" . env("LOADCENTRAL_PWDKEY") .  "')");

        $branch->update($params);
        \Session::flash('success', "Branch {$branch->ShortName} has been updated!");
        return redirect(route('branchs.edit', [$branch]));
    }
}
