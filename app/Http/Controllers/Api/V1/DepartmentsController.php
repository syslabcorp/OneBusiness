<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\Corporation;
use App\Transformers\T\DeptsTransformer;

class DepartmentsController extends Controller
{
    public function index(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);
      
        $deptModel = new \App\Models\T\Depts;
        $deptModel->setConnection($company->database_name);

        $departments = $deptModel->get();

        return fractal($departments, new DeptsTransformer)->toJson();
    }
}
