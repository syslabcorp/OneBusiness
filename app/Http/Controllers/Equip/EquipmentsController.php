<?php

namespace App\Http\Controllers\Equip;

use App\Http\Controllers\Controller;
use App\Company;
use Illuminate\Http\Request;
use App\Models\Corporation;

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

        return view('equipments.create', [
            'tab' => $tab,
            'equipment' => $equipment,
            'deptItems' => $deptItems,
            'branches' => $branches,
            'vendors' => $vendors
        ]);
    }

    public function store()
    {
        $company = Corporation::findOrFail(request()->corpID);

        \App\Models\Equip\Hdr::create(
            request()->only([
                'description', 'branch', 'dept_id', 'type', 'jo_dept'
            ])
        );

        return redirect(route('equipments.index', ['corpID' => request()->corpID]));
    }
}
