<?php

namespace App\Http\Controllers;

use App\Branch;
use App\RateTemplate;
use App\RateSchedule;
use Illuminate\Http\Request;

class RatesController extends Controller
{
  public function index(Request $request, Branch $branch) {

    if($request->get('tmplate_id')) {
      $rate = RateTemplate::find($request->get('tmplate_id'));
    } else {
      $rate = $branch->rates()->first();
    }
    if(!$rate) {
      $rate = new RateTemplate;
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

    $year = empty($request->get('year')) ? date('Y') : $request->get('year');
    $months = empty($request->get('months')) ? [0] : $request->get('months');

    $monthsStr = implode(',', $months);

    $schedules = $branch->schedules()->whereYear("rate_date", '=', $year)
                        ->whereRaw("MONTH(rate_date) IN ($monthsStr)")
                        ->orderBy('rate_date', 'asc')->get();

    return view('rates.index', [
      'branch' => $branch,
      'action' => $request->get('action'),
      'rate' => $rate,
      'year' => $year,
      'months' => $months,
      'schedules' => $schedules
    ]);
  }

  public function store(Request $request, Branch $branch) {
    $this->validate($request,[
      'tmplate_name' => 'required',
    ]);

    $branch->rates()->create($request->only(
      'charge_mode', 'ZoneStart1', 'ZoneStart2', 'ZoneStart3', 'DiscStubPrint', "DiscStubMsg",
      'DiscValidity', 'Discount1', 'Discount2', 'Discount3', 'MinimumChrg', 'MinimumTime',
      'Modified', 'tmplate_name', 'Color'
    ));

    \Session::flash('success', "Rate Template has been created.");
    return redirect(route('branchs.rates.index', [$branch]));
  }

  public function update(Request $request, Branch $branch, RateTemplate $rate) {
    $this->validate($request,[
      'tmplate_name' => 'required',
    ]);

    $rate->update($request->only(
      'charge_mode', 'ZoneStart1', 'ZoneStart2', 'ZoneStart3', 'DiscStubPrint', "DiscStubMsg",
      'DiscValidity', 'Discount1', 'Discount2', 'Discount3', 'MinimumChrg', 'MinimumTime',
      'Modified', 'tmplate_name', 'Color'
    ));

    \Session::flash('success', "Rate Template has been updated.");
    return redirect(route('branchs.rates.index', [$branch, 'tmplate_id' => $rate->tmplate_id]));
  }

  public function details(Request $request, Branch $branch, RateTemplate $rate) {
    foreach($rate->details()->get() as $detail) {
      $params = $request->get('detail')[$detail->nKey];
      foreach($params as $key => $value) {
        $params[$key] = floatval($value);
      }
      $detail->update($params);
    }
    \Session::flash('success', "Rate has been updated.");

    return redirect(route('branchs.rates.index', [$branch, 'tmplate_id' => $rate->tmplate_id]));
  }

  public function assign(Request $request, Branch $branch) {
    if(empty($request->get('tmplate_id')) || empty($request->get('start_date')) || empty($request->get('end_date'))) {
      \Session::flash('error', "Can't assign rate template");
    }else {
      $startDate = new \DateTime($request->get('start_date'));
      $endDate = new \DateTime($request->get('end_date'));
      $endDate->modify('+1 days');
      $interval = \DateInterval::createFromDateString('1 day');
      $period = new \DatePeriod($startDate, $interval, $endDate);

      foreach ( $period as $date) {
        if(array_search($date->format("D"), $request->get('days')) !== false) {
          $schedule = $branch->schedules()->where("rate_date", '=', $date->format('Y-m-d'))->first();
          if($schedule) {
            $schedule->update(['tmplate_id' => $request->get('tmplate_id')]);
          }else {
            $branch->schedules()->create([
              'tmplate_id' => $request->get('tmplate_id'),
              'rate_date' => $date->format('Y-m-d')
            ]);
          }
        }
      }
    }

    \Session::flash('success', "Rate Template has beed assigned.");
    return redirect(route('branchs.rates.index', [$branch, '#schedule']));
  }
}
