<?php

namespace App\Http\Controllers\Equip;

use App\Http\Controllers\Controller;
use App\Company;
use Illuminate\Http\Request;
use App\Models\Corporation;
use App\Http\Requests\Equip\EquipmentRequest;

class EquipmentsController extends Controller
{
    public function index()
    {
        if(!\Auth::user()->checkAccessById(56, 'V')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $companies = Corporation::orderBy('corp_name')
                                ->where('database_name', '!=', '')
                                ->get();
        if (request()->corpID) {
            $company = Corporation::findOrFail(request()->corpID);
        } else {
            $company = $companies->first();
        }
      
        return view('equipments.index', [
            'companies' => $companies,
            'company' => $company
        ]);
    }

    public function create()
    {
        if(!\Auth::user()->checkAccessById(56, 'A')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }
        
        $company = Corporation::findOrFail(request()->corpID);
        $tab = 'auto';
        
        $deptModel = new \App\Models\T\Depts;
        $deptModel->setConnection($company->database_name);

        $deptItems = $deptModel->orderBy('department', 'ASC')
                                ->get();
        
        $branches = \Auth::user()->getBranchesByArea(request()->corpID);
        

        $equipment = new \App\Models\Equip\Hdr;
        $equipment->isActive = 1;

        $lastEquipment = $equipment->orderBy('asset_id', 'DESC')->first();

        $lastAssetId = $lastEquipment ? $lastEquipment->asset_id + 1 : 1;
        
        $vendors = \App\Models\Vendor::orderBy('VendorName', 'ASC')->get();
        $brands = \App\Models\Equip\Brands::orderBy('description', 'ASC')->get();
        $categories = \App\Models\Equip\Category::orderBy('description', 'ASC')->get();

        return view('equipments.create', [
            'tab' => $tab,
            'equipment' => $equipment,
            'deptItems' => $deptItems,
            'branches' => $branches,
            'vendors' => $vendors,
            'brands' => $brands,
            'categories' => $categories,
            'lastAssetId' =>$lastAssetId
        ]);
    }

    public function store(EquipmentRequest $request)
    {
        if(!\Auth::user()->checkAccessById(56, 'A')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }
        
        $equipParams = request()->only([
            'description', 'type'
        ]);
       
        $equipParams['isActive'] = request()->active ? 1 : 0;

        $equipment = \App\Models\Equip\Hdr::create($equipParams);
   
        if (is_array(request()->parts)) {
            foreach (request()->parts as $partParams) {
                if (!empty($partParams['item_id'])) {
                    \App\Models\Equip\Detail::create([
                        'asset_id' => $equipment->asset_id,
                        'item_id' => $partParams['item_id'],
                        'qty' => $partParams['qty']
                    ]);

                    \App\Models\Item\Master::where('item_id', '=', $partParams['item_id'])
                        ->update([
                            'LastCost' => $partParams['lastcost']
                        ]);
                }
            }
        }
        
        \Session::flash('success', 'New equipment #' . $equipment->asset_id . '-' . $equipment->description . ' has been created');

        return redirect(route('equipments.index', ['corpID' => request()->corpID]));
    }

    public function show($id)
    {
        if(!\Auth::user()->checkAccessById(56, 'V')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $company = Corporation::findOrFail(request()->corpID);
        $tab = 'auto';

        $equipment = \App\Models\Equip\Hdr::findOrFail($id);

        $deptModel = new \App\Models\T\Depts;
        $deptModel->setConnection($company->database_name);

        $deptItems = $deptModel->orderBy('department', 'ASC')
                                ->get();
        
        $branches = \Auth::user()->getBranchesByArea(request()->corpID);
        $vendors = \App\Models\Vendor::orderBy('VendorName', 'ASC')->get();
        $brands = \App\Models\Equip\Brands::orderBy('description', 'ASC')->get();
        $categories = \App\Models\Equip\Category::orderBy('description', 'ASC')->get();
        
        $histories = $equipment->histories()
                            ->selectRaw('*, DATE_FORMAT(created_at, "%m/%d/%Y") as log_at')
                            ->orderBy('created_at', 'DESC')
                            ->get()
                            ->groupBy('log_at');
        
        return view('equipments.edit', [
            'tab' => $tab,
            'equipment' => $equipment,
            'deptItems' => $deptItems,
            'branches' => $branches,
            'vendors' => $vendors,
            'brands' => $brands,
            'categories' => $categories,
            'histories' => $histories
        ]);
    }

    public function update(EquipmentRequest $request, $id)
    {
        if(!\Auth::user()->checkAccessById(56, 'E')) {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }
     
        $company = Corporation::findOrFail(request()->corpID);

        $equipment = \App\Models\Equip\Hdr::findOrFail($id);

        $equipParams = request()->only([
            'description', 'type', 'jo_dept'
        ]);

        $equipParams['isActive'] = request()->active ? 1 : 0;

        $equipment->update($equipParams);

        $equipment->details()->delete();
       
        if (is_array(request()->parts)) {
            foreach (request()->parts as $partParams) {
                if (!empty($partParams['item_id'])) {
                    \App\Models\Equip\Detail::create([
                        'asset_id' => $equipment->asset_id,
                        'item_id' => $partParams['item_id'],
                        'qty' => $partParams['qty']
                    ]);

                    \App\Models\Item\Master::where('item_id', '=', $partParams['item_id'])
                        ->update([
                            'LastCost' => $partParams['lastcost']
                        ]);
                } 
            }
        } else {
            $equipment->details->each->delete();
        }

        \Session::flash('success', 'Equipment #' . $equipment->asset_id . '-' . $equipment->description . ' has been updated');
        
        return redirect(route('equipments.show', [$equipment, 'corpID' => request()->corpID]));
    }
}
