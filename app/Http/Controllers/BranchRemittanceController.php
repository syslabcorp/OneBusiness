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
    $checked = false;
    // if($request->start_date || $request->end_date)
    // {
    //   $checked = true;
    //   if(!$request->start_date)
    //   {
    //     $remittances = TRemittance::join('t_shifts','t_remitance.Shift_ID', '=', 't_shifts.Shift_ID')->where('ShiftDate', '<=', $request->end_date)->get();
    //   }
    //   elseif(!$request->end_date)
    //   {
    //     $remittances = TRemittance::join('t_shifts','t_remitance.Shift_ID', '=', 't_shifts.Shift_ID')->where('ShiftDate', '>=', $request->start_date)->get() ;
    //   }
    //   else
    //   {
    //     $remittances = TRemittance::join('t_shifts','t_remitance.Shift_ID', '=', 't_shifts.Shift_ID')->where('ShiftDate', '<=', $request->end_date)->where('ShiftDate', '>=', $request->start_date)->get();
    //   }
    // }
    // else
    // {
    //   $remittances = TRemittance::join('t_shifts','t_remitance.Shift_ID', '=', 't_shifts.Shift_ID')->get();
    // }

    $remittance_collections = RemittanceCollection::all();

    return view('t_remittances.index', [
      'remittance_collections' => $remittance_collections ,
      'checked' => $checked,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date
    ]);
  }

  public function show($id)
  {
    $shifts =  \Auth::user()->shifts()->get();
    return view('t_remittances.show', [
      'shifts' => $shifts
    ]);
  }

  public function  renderModal(Request $request)
  {
    // return response($request);
    $shift = Shift::where('Shift_ID', $request->id)->first();
    $array = array(
      "cashier"=> "",
      "shift_id"=> $request->id,
      "total_sales"=> "",
      "total_shortage"=> $shift->remittance->Adj_Amt,
      'total_remittance'=> $shift->remittance->TotalRemit,
      'couterchecked'=> "",
      'wrong_input'=> $shift->remittance->Wrong_Input,
      'adj_short'=> $shift->remittance->Adj_Short,
      'shortage'=> $shift->remittance->Adj_Amt,
      'remarks'=> $shift->remittance->Notes
    );

    return response()->json($array);
  }

  public function create(Request $request)
  {
    $groupIds = explode(",", \Auth::user()->group_ID);
    $selectStatus = $request->groupStatus != null ? $request->groupStatus : 1;
    $remitGroups = RemitGroup::where('status', '=', $selectStatus)->whereIn('group_ID', $groupIds)->get();

    $cities = City::orderBy('City', 'ASC')->get();
    $selectCity = $cities->first();

    $selectGroup = $remitGroups->first();
    

    if($request->cityId) {
      $selectCity = City::find($request->cityId);
    }

    if($request->groupId) {
      $selectGroup = RemitGroup::find($request->groupId);
    }

    if($selectGroup) {
      $branchIds = explode(",", $selectGroup->branch);
    }else {
      $branchIds = [];
    }

    $branchs = Branch::where('City_ID', $request->cityId)->whereIn('Branch', $branchIds)->get();

    return view('t_remittances.create', [
      'remitGroups' => $remitGroups,
      'selectGroup' => $selectGroup,
      'cities' => $cities,
      'selectCity' => $selectCity,
      'branchs' => $branchs,
      'selectStatus' => $selectStatus
    ]);
  }

  public function storeCollections(Request $request)
  {
    $rules = [];
    $niceNames = [];
    
    foreach($request->collections as $index => $collection) {
      $min = intval($collection['Start_CRR']) + 1;
      $rules["collections.{$index}.End_CRR"] = "numeric|min:{$min}";
      $rules["collections.{$index}.Total_Collection"] = "numeric";

      $niceNames["collections.{$index}.End_CRR"] = 'Input';
      $niceNames["collections.{$index}.Total_Collection"] = 'Input';
    }
    $this->validate($request, $rules, [], $niceNames);

    foreach($request->collections as $key => $collection) {
      $collection['Time_Create'] = date('Y-m-d H:i:s');
      $collection['UserID'] = \Auth::user()->UserID;
      RemittanceCollection::updateOrCreate(['ID' => $collection['ID']], $collection);
    }

    \Session::flash('success', "Remittance collections has been updated successfully.");
    return redirect(route('branch_remittances.create', ['cityId' => $request->cityId, 'groupId' => $request->groupId]));
  }

  public function store(Request $request)
  {

    $shift = Shift::where('Shift_ID', $request->get('Shift_ID'))->first();
    $params = $request->only(['Shift_ID', 'TotalRemit', 'Wrong_Input', 'Adj_Short' ]);
    
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
