<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Company;
use Illuminate\Http\Request;
use App\Models\Corporation;

class BrandsController extends Controller
{
    public function index()
    {
        $brands = \App\Models\Equip\Brands::all();

        return view('assets.brands.index', [
            'brands' => $brands
        ]);
    }

}
