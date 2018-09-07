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
        return view('equipments.create');
    }
}
