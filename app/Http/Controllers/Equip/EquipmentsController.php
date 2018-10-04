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

        $company = Corporation::findOrFail(request()->corpID);

        $equipParams = request()->only([
            'description', 'branch', 'dept_id', 'type', 'jo_dept'
        ]);

        $equipParams['isActive'] = request()->active ? 1 : 0;

        $equipment = \App\Models\Equip\Hdr::create($equipParams);

        if (is_array(request()->parts)) {
            foreach (request()->parts as $partParams) {
                $item = \App\Models\Item\Master::create([
                    'description' => $partParams['desc'],
                    'brand_id' => $partParams['brand_id'],
                    'cat_id' => $partParams['cat_id'],
                    'supplier_id' => $partParams['supplier_id'],
                    'consumable' => isset($partParams['consumable']) ? 1 : 0,
                    'isActive' => isset($partParams['isActive']) ? 1 : 0
                ]);

                \App\Models\Equip\History::create([
                    'changed_by' => \Auth::user()->UserID,
                    'content' => 'details has been created',
                    'item' => 'Part #' . $item->item_id . ' - ' . $item->description,
                    'equipment_id' => $equipment->asset_id
                ]);

                $equipment->details()->create([
                    'item_id' => $item->item_id,
                    'qty' => isset($partParams['qty']) ? $partParams['qty'] : 0
                ]);
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
            'description', 'branch', 'dept_id', 'type', 'jo_dept'
        ]);

        $equipParams['isActive'] = request()->active ? 1 : 0;

        $equipment->update($equipParams);

        if (is_array(request()->parts)) {
            $equipment->details->each(function($item, $index) {
                $listParts = collect(request()->parts);
                if (!$listParts->where('item_id', $item->item_id)->first()) {
                    $item->delete();
                }
            });

            foreach (request()->parts as $partParams) {
                if (isset($partParams['item_id'])) {
                    $item = \App\Models\Item\Master::find($partParams['item_id']);

                    $item->fill([
                        'description' => $partParams['desc'],
                        'brand_id' => $partParams['brand_id'],
                        'cat_id' => $partParams['cat_id'],
                        'supplier_id' => $partParams['supplier_id'],
                        'consumable' => isset($partParams['consumable']) ? 1 : 0,
                        'isActive' => isset($partParams['isActive']) ? 1 : 0
                    ]);

                    if ($item->isDirty()) {
                        \App\Models\Equip\History::create([
                            'changed_by' => \Auth::user()->UserID,
                            'content' => 'details has been updated',
                            'item' => 'Part #' . $item->item_id . ' - ' . $item->description,
                            'equipment_id' => $equipment->asset_id
                        ]);
                    }

                    $item->save();
                } else {
                    $item = \App\Models\Item\Master::create([
                        'description' => $partParams['desc'],
                        'brand_id' => $partParams['brand_id'],
                        'cat_id' => $partParams['cat_id'],
                        'supplier_id' => $partParams['supplier_id'],
                        'consumable' => isset($partParams['consumable']) ? 1 : 0,
                        'isActive' => isset($partParams['isActive']) ? 1 : 0
                    ]);

                    \App\Models\Equip\History::create([
                        'changed_by' => \Auth::user()->UserID,
                        'content' => 'details has been created',
                        'item' => 'Part #' . $item->item_id . ' - ' . $item->description,
                        'equipment_id' => $equipment->asset_id
                    ]);
                }
                \App\Models\Equip\Detail::updateOrCreate([
                    'item_id' => $item->item_id,
                    'asset_id' => $equipment->asset_id
                ],[
                    'qty' => isset($partParams['qty']) ? $partParams['qty'] : 0
                ]);
            }
        } else {
            $equipment->details->each->delete();
        }

        \Session::flash('success', 'Equipment #' . $equipment->asset_id . '-' . $equipment->description . ' has been updated');
        
        return redirect(route('equipments.show', [$equipment, 'corpID' => request()->corpID]));
    }
}
