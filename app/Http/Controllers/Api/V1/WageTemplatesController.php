<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Http\Controllers\Controller;
use App\Corporation;
use App\Transformers\WageTmpl8\MstrTransformer;

class WageTemplatesController extends Controller
{
    public function index(Request $request)
    {
        $company = Corporation::findOrFail($request->corpID);
      
        $mstrModel = new \App\Models\WageTmpl8\Mstr;
        $mstrModel->setConnection($company->database_name);

        $templates = $mstrModel->get();

        return fractal($templates, new MstrTransformer)->toJson();
    }
}
