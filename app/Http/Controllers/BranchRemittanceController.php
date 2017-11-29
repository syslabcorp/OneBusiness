<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TRemittance;
use App\RemitGroup;
use App\City;
use App\Shift;
use App\Branch;
use App\RemittanceCollection;
use App\Corporation;

class BranchRemittanceController extends Controller
{
  public function index(Request $request)
  {
    $checked = false;
    if($request->start_date || $request->end_date)
    {
      $checked = true;
      if(!$request->start_date)
      {
        $remittance_collections = RemittanceCollection::where('Time_Create', '<=', $request->end_date)->get();
      }
      elseif(!$request->end_date)
      {
        $remittance_collections = RemittanceCollection::where('Time_Create', '>=', $request->start_date)->get() ;
      }
      else
      {
        $remittance_collections = RemittanceCollection::where('Time_Create', '<=', $request->end_date)->where('Time_Create', '>=', $request->start_date)->get();
      }
    }
    else
    {
    $remittance_collections = RemittanceCollection::all();
    }

    return view('t_remittances.index', [
      'corpID' => $request->corpID,
      'remittance_collections' => $remittance_collections ,
      'checked' => $checked,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date
    ]);
  }

  public function show($id)
  {
    $remittance_collection = RemittanceCollection::where('ID', $id)->first();
    $corp_type = $remittance_collection->branch()->first()->corp()->first()->corp_type;
    $shifts = Shift::whereBetween('Shift_ID', [$remittance_collection->Start_CRR, $remittance_collection->End_CRR])
    ->get();
    foreach($shifts as $key => $shift)
    {
      $array_shift["{$shift->branch()->first()->ShortName}"]["{$shift->ShiftDate->format('D,M-d-Y')}"][] = $shift;
    }
    // return response($array_shift);
    
    return view('t_remittances.show', [
      'shifts_by_branch' => $array_shift,
      'remittance_collection_ID' => $remittance_collection->ID,
      'corp_type' => $corp_type
    ]);
  }

  public function  renderModal(Request $request)
  {
    $shift = Shift::where('Shift_ID', $request->id)->first(); 
    if( $shift->remittance )
    {
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
    }
    else
    {
      $array = array(
        "cashier"=> "",
        "shift_id"=> $request->id,
        "total_sales"=> "",
        "total_shortage"=> "",
        'total_remittance'=>"",
        'couterchecked'=> "",
        'wrong_input'=> "",
        'adj_short'=> "",
        'shortage'=> "",
        'remarks'=> ""
      );
    }


    return response()->json($array);
  }

  public function create(Request $request)
  {
    $corp = Corporation::find($request->corpID);
    $groupIds = explode(",", \Auth::user()->group_ID);
    $selectStatus = $request->groupStatus != null ? $request->groupStatus : 1;
    $remitGroups = RemitGroup::where('status', '=', $selectStatus)->whereIn('group_ID', $groupIds)->get();

    $selectGroup = $remitGroups->first();
    
    if($request->groupId) {
      $selectGroup = RemitGroup::whereIn('group_ID', $groupIds)->find($request->groupId);
    }

    if($selectGroup) {
      $branchIds = explode(",", $selectGroup->branch);
    }else {
      $branchIds = [];
    }

    $citiIDs = [];

    foreach($branchIds as $id)
    {
      if(Branch::find($id)->city)
      {
        $cityID = Branch::find($id)->city()->first()->City_ID;
        array_push($citiIDs, $cityID);
      }
    }
    
    $cities = City::whereIn('City_ID', $citiIDs)->get();

    $selectCity = $cities->first();

    if($request->cityId) {
      $selectCity = City::find($request->cityId);
    }

    if($selectCity)
    {
      $branchs = Branch::where('City_ID', $selectCity->City_ID)->where('corp_id', $request->corpID)->whereIn('Branch', $branchIds)->get();
    }
    else
    {
      $branchs = [];
    }
    return view('t_remittances.create', [
      'corpID' => $request->corpID,
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
      $rules["collections.{$index}.End_CRR"] = "numeric|min:{$min}|nullable";
      $rules["collections.{$index}.Total_Collection"] = "numeric|nullable";

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
    return redirect(route('branch_remittances.create', [ 'corpID' => $request->corpID, 'cityId' => $request->cityId, 'groupId' => $request->groupId]));
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
    
    return redirect()->route('branch_remittances.show', $request->remittance_collection_ID );
  }

}
