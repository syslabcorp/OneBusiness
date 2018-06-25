<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Request;
use App\Models\Corporation;
use App\Http\Requests\CorporationRequest;
use DB;


class CorporationsController extends Controller
{
    public function index()
    {
        if(!\Auth::user()->checkAccessById(30, "V"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $corporations = Corporation::orderBy('corp_name', 'asc')
                                    ->where('deleted', 0)
                                    ->get();

        return view('corporations.index', [
            'corporations' => $corporations
        ]);
    }

    public function create()
    {
        if(!\Auth::user()->checkAccessById(30, "A"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }

        $types = Corporation::groupBy('corp_type')->select('corp_type')->get();

        return view('corporations.create', [
            'types' => $types
        ]);
    }

    public function store(CorporationRequest $request)
    {
        Corporation::create([
            'corp_name' => request()->corp_name,
            'corp_type' => request()->corp_type,
            'database_name' => request()->database_name,
            'created_at' => date("Y-m-d H:i:s")
        ]);

        \Session::flash('success', 'Corporation has been created');

        return redirect(route('corporations.index'));
    }

    public function edit($id)
    {
        $corporation = Corporation::findOrFail($id);

        $types = Corporation::groupBy('corp_type')->select('corp_type')->get();

        return view('corporations.edit', [
            'corporation' => $corporation,
            'types' => $types
        ]);
    }

    public function update(CorporationRequest $request, $id)
    {
        $corporation = Corporation::findOrFail($id);

        $corporation->update([
            'corp_name' => request()->corp_name,
            'corp_type' => request()->corp_type,
            'database_name' => request()->database_name,
            'modified_at' => date("Y-m-d H:i:s")
        ]);

        \Session::flash('success', 'Corporation has been updated');

        return redirect(route('corporations.index'));
    }

    public function destroy($id)
    {
        $corporation = Corporation::findOrFail($id);

        $corporation->update([
            'deleted' => 1
        ]);

        \Session::flash('success', 'Corporation has been deleted');

        return response()->json([
            'success' => true
        ]);
    }
}