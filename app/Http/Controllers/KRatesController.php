<?php

namespace App\Http\Controllers;

use App\Branch;
use App\KRateTemplate;
use Illuminate\Http\Request;

class KRatesController extends Controller
{
  public function index(Request $request, Branch $branch) {
    if($request->get('tmplate_id')) {
      $rate = KRateTemplate::find($request->get('tmplate_id'));
    } else {
      $rate = $branch->krates()->first();
    }
    if(!$rate) {
      $rate = new KRateTemplate;
    }

    if($rate->tmplate_id) {
      $totalDetails = $rate->details()->count();
      if($branch->MaxUnits > $totalDetails) {
        $macs = $branch->macs()->orderBy("nKey", "DESC")->take($branch->MaxUnits - $totalDetails)->get();
        foreach($macs as $mac) {
          $rate->details()->create(["nKey" => $mac->nKey]);
        }
      }else if($branch->MaxUnits < $totalDetails) {
        for($i = 0; $i < $totalDetails - $branch->MaxUnits; $i++)
        {
          $rate->details()->orderBy("nKey", "DESC")->first()->delete();
        }
      }
    }

    return view('krates.index', [
      'branch' => $branch,
      'action' => $request->get('action'),
      'rate' => $rate
    ]);
  }

  public function create(Request $request, Branch $branch) {
    if(!\Auth::user()->checkAccessById(2, "A"))
    {
        \Session::flash('error', "You don't have permission"); 
        return redirect("/home"); 
    }

    return view('krates.create', [
      'branch' => $branch
    ]);
  }
  
  public function edit(Request $request, Branch $branch) {
    $rate = KRateTemplate::find($request->get('tmplate_id'));
    
    if(!\Auth::user()->checkAccessById(2, "E") || !$rate)
    {
        \Session::flash('error', "You don't have permission"); 
        return redirect("/home"); 
    }
    return view('krates.edit', [
      'branch' => $branch,
      'rate' => $rate
    ]);
  }

  public function store(Request $request, Branch $branch) {
    if(!\Auth::user()->checkAccessById(2, "A"))
    {
        \Session::flash('error', "You don't have permission"); 
        return redirect("/home"); 
    }


    $this->validate($request,[
      'tmplate_name' => 'required|max:20',
    ]);
    $params = $request->only('active', 'tmplate_name');
    $params['active'] = empty($params['active']) ? 0 : $params['active'];

    $template = $branch->krates()->create($params);
    foreach($request->get('detail') as $detail) {
      $template->details()->create($detail);
    }

    \DB::table('s_changes')->where('Branch', '=', $branch->Branch)->update([
      'rates' => 1,
      'services' => 1
    ]);

    \Session::flash('success', "Rate Template has been created.");
    return redirect(route('branchs.krates.index', [$branch]));
  }

  public function update(Request $request, Branch $branch) {
    $rate = KRateTemplate::find($request->get('tmplate_id'));

    if(!\Auth::user()->checkAccessById(2, "E") || !$rate)
    {
        \Session::flash('error', "You don't have permission"); 
        return redirect("/home"); 
    }

    $this->validate($request,[
      'tmplate_name' => 'required|max:20',
    ]);

    $params = $request->only('active', 'tmplate_name');
    $params['active'] = empty($params['active']) ? 0 : $params['active'];

    $rate->update($params);

    foreach($rate->details()->get() as $detail) {
      $params = $request->get('detail')[$detail->nKey];
      foreach($params as $key => $value) {
        $params[$key] = floatval($value);
      }
      $detail->update($params);
    }

    \DB::table('s_changes')->where('Branch', '=', $branch->Branch)->update([
      'rates' => 1
    ]);

    \Session::flash('success', "Rate Template has been updated.");
    return redirect(route('branchs.krates.index', [$branch]));
  }
}
