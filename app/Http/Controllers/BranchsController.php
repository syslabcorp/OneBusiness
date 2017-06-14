<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;

class BranchsController extends Controller
{
    public function index()
    {
        $branchs = Branch::orderBy('updated_at', 'DESC')->get();

        $result = [];
        foreach($branchs as $branch)
        {
            if(!isset($result[$branch->city->province->id]['count']))
            {
                $result[$branch->city->province->id]['count'] = 0;
            }
            $result[$branch->city->province->id]['cities'][$branch->city_id][] = $branch;
            $result[$branch->city->province->id]['count'] += 1;
        }
        return view('branchs.index', [
            'branchs' => $result
        ]);
    }

    public function create()
    {
        return view('branchs.create');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'branch_name' => 'required',
            'operator' => 'required',
            'street' => 'required',
            'province' => 'required',
            'city' => 'required',
            'units' => 'required|numeric'
        ]);
        $params = $request->all();
        $params['description'] = $params['operator'];
        $params['city_id'] = $params['city'];
        $params['max_units'] = $params['units'];

        Branch::create($params);

        return redirect(route('branchs.index'));
    }

    public function show(Post $post)
    {
        //
    }

    public function edit(Post $post)
    {
        return $post;
    }

    public function update(Request $request, Post $post)
    {
        //
    }

    public function destroy(Post $post)
    {
        //
    }
}
