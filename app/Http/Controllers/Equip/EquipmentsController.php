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
        $company = Corporation::findOrFail(request()->corpID);
        
        $deptModel = new \App\Models\T\Depts;
        $deptModel->setConnection($company->database_name);

        $deptItems = $deptModel->orderBy('department', 'ASC')
                                ->get();
        
        $branches = \Auth::user()->getBranchesByArea(request()->corpID);

        return view('equipments.index', [
            'deptItems' => $deptItems,
            'branches' => $branches
        ]);
    }

    public function create()
    {
        $company = Corporation::findOrFail(request()->corpID);
        $tab = 'auto';
        
        $deptModel = new \App\Models\T\Depts;
        $deptModel->setConnection($company->database_name);

        $deptItems = $deptModel->orderBy('department', 'ASC')
                                ->get();
        
        $branches = \Auth::user()->getBranchesByArea(request()->corpID);
        

        $equipment = new \App\Models\Equip\Hdr;

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
            'categories' => $categories
        ]);
    }

    public function store(EquipmentRequest $request)
    {
        $company = Corporation::findOrFail(request()->corpID);

        $equipment = \App\Models\Equip\Hdr::create(
            request()->only([
                'description', 'branch', 'dept_id', 'type', 'jo_dept'
            ])
        );

        if (is_array(request()->parts)) {
            foreach (request()->parts as $partParams) {
                $item = \App\Models\Item\Master::create([
                    'description' => $partParams['desc'],
                    'brand_id' => $partParams['brand_id'],
                    'cat_id' => $partParams['cat_id'],
                    'supplier_id' => $partParams['supplier_id'],
                    'consumable' => isset($partParams['consumable']) ? 1 : 0
                ]);

                $equipment->details()->create([
                    'item_id' => $item->item_id,
                    'status' => $partParams['status']
                ]);
            }
        }

        return redirect(route('equipments.index', ['corpID' => request()->corpID]));
    }

    public function show($id)
    {
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

        return view('equipments.edit', [
            'tab' => $tab,
            'equipment' => $equipment,
            'deptItems' => $deptItems,
            'branches' => $branches,
            'vendors' => $vendors,
            'brands' => $brands,
            'categories' => $categories
        ]);
    }

    public function update(EquipmentRequest $request, $id)
    {
        $company = Corporation::findOrFail(request()->corpID);

        $equipment = \App\Models\Equip\Hdr::findOrFail($id);

        $equipment->update(
            request()->only([
                'description', 'branch', 'dept_id', 'type', 'jo_dept'
            ])
        );

        $equipment->details->each->delete();

        if (is_array(request()->parts)) {
            foreach (request()->parts as $partParams) {
                $item = \App\Models\Item\Master::create([
                    'description' => $partParams['desc'],
                    'brand_id' => $partParams['brand_id'],
                    'cat_id' => $partParams['cat_id'],
                    'supplier_id' => $partParams['supplier_id'],
                    'consumable' => isset($partParams['consumable']) ? 1 : 0
                ]);

                $equipment->details()->create([
                    'item_id' => $item->item_id,
                    'status' => $partParams['status']
                ]);
            }
        }

        \Session::flash('success', 'Equipment has been updated successfully');
        
        return redirect(route('equipments.show', [$equipment, 'corpID' => request()->corpID]));
    }
}
