<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Company;
use Illuminate\Http\Request;
use App\Models\Corporation;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Equip\Category::all();

        return view('assets.categories.index', [
            'categories' => $categories
        ]);
    }

}
