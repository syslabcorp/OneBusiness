<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Equip\Brands;
use App\Models\Equip\Category;
use App\Models\Vendor;

class PartsController extends Controller
{
    public function index(){
        $brands = Brands::orderBy('description')->get();
        $categories = Category::orderBy('description')->get();
        $vendors = Vendor::orderBy('VendorName')->get();

        return view('parts.index', [
            'brands' => $brands,
            'categories' => $categories,
            'vendors' => $vendors
        ]);
    }

    public function create(){
        dd('ok');
    }
}
