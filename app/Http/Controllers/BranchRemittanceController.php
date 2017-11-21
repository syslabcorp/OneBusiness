<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TRemittance;
use App\RemitGroup;
use App\City;
use App\Shift;
use App\Branch;
use App\RemittanceCollection;

class BranchRemittanceController extends Controller
{
  public function index(Request $request)
  {
    return view('t_remittances.index', [
      'remittances' => TRemittance::all()
    ]);
  }

  public function show($id)
  {
    $shifts =  \Auth::user()->shifts()->get();
    // return response()->json($shifts);
    return view('t_remittances.show', [
      'shifts' => $shifts
    ]);
  }

  public function create(Request $request)
  {
    $remit_groups = RemitGroup::all();
    $cities = City::all();
    
    if($request)
    {
      $remit_group = RemitGroup::where('group_ID', $request->remit_group )->get()->first();
      // dd($remit_group);
      $brs = explode(",", $remit_group->branch );
      $branchs = Branch::where('City_ID', $request->city)->whereIn('Branch', $brs )->get();
      $city = $request->city;
    }
    else
    {
      $remit_group = $remit_groups->first();
      $brs = explode(",", $remit_group->branch );
      $branchs = Branch::where('City_ID', $cities->first()->City_ID)->whereIn('Branch', $brs )->get();
      $city = $cities->first()->City_ID;
    }
    
    return view('t_remittances.create', [
      'remit_groups' => $remit_groups,
      'remittance_group' => $remit_group,
      'cities' => $cities,
      'city_ID' => $city,
      'brs' => $brs,
      'branchs' => $branchs
    ]);
    // return response()->json($a);
  }

  public function store_collections(Request $request)
  {
    foreach($request->collections as $key => $collection)
    {
      if(!empty($collection['End_CRR']) || !empty($collection['Total_Collection']))
      {
        RemittanceCollection::create(['End_CRR' => $collection['End_CRR'], 'Start_CRR' => $collection['Start_CRR'], 'Total_Collection' => $collection['Total_Collection'], 'Branch' => $key  ]);
      }
    }
    return redirect()->route('branch_remittances.create' );
  }

  public function store(Request $request)
  {
    $shift = Shift::where('Shift_ID', $request->get('Shift_ID'))->first();
    $params = $request->only(['Shift_ID', 'TotalRemit', 'Wrong_Input', 'Adj_Short', 'Notes' ]);
    
    if ( empty($params['Wrong_Input'])  )
    {
      $params['Wrong_Input'] = '0';
    }

    if (empty($params['Adj_Short']) )
    {
      $params['Adj_Short'] = '0';
    }

    if ($shift->remittance()->count())
    {
      
      $shift->remittance()->update($params);
    }
    else
    {
      $shift->remittance()->create($params);
    }
    return redirect()->route('branch_remittances.show', 3 );
  }

}
