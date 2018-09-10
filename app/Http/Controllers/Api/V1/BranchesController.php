<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Corporation;
use App\Http\Controllers\Controller;

class BranchesController extends Controller
{
    public function getDepts($id)
    {
        $company = Corporation::findOrFail(request()->corpID);

        $deptModel = new \App\Models\T\BranchDepts;
        $deptModel->setConnection($company->database_name);

        $depts = $deptModel->where('branch', $id)->pluck('dept');

        return response()->json(['depts' => $depts]);
    }
}
