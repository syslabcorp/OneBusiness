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
    $company = Corporation::findOrFail($request->corpID);

    $collections = new RemittanceCollection;
    $collections->setConnection($company->database_name);

    if($request->start_date) {
      $collections = $collections->whereDate('CreatedAt', '>=', $request->start_date);
    }

    if($request->end_date) {
      $collections = $collections->whereDate('CreatedAt', '<=', $request->end_date);
    }

    return view('t_remittances.index', [
      'corpID' => $request->corpID,
      'collections' => $collections->get(),
      'checked' => $checked,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date
    ]);
  }

  public function show(Request $request, $id)
  {
    $company = Corporation::findOrFail($request->corpID);

    $collectionModel = new RemittanceCollection;
    $collectionModel->setConnection($company->database_name);

    $collection = $collectionModel->findOrFail($id);

    return view('t_remittances.show', [
      'collection' => $collection,
      'company' => $company
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

  public function create(Request $request, $id)
  {
    $corp = Corporation::find($request->corpID);
    $groupIds = explode(",", \Auth::user()->group_ID);
    $selectStatus = $request->groupStatus != null ? $request->groupStatus : 1;
    $remitGroups = RemitGroup::where('status', '=', $selectStatus)->whereIn('group_ID', $groupIds)->get();

    $collection = null;
    if($id) {
      $collectionModel = new RemittanceCollection;
      $collectionModel->setConnection($corp->database_name);

      $collection = $collectionModel->findOrFail($id);
    }

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

    foreach($branchIds as $id) {
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

    if($selectCity) {
      $branchs = Branch::where('City_ID', $selectCity->City_ID)->where('corp_id', $request->corpID)->whereIn('Branch', $branchIds)->get();
    }else
    {
      $branchs = [];
    }
    return view($collection ? 't_remittances.edit' : 't_remittances.create', [
      'corpID' => $request->corpID,
      'remitGroups' => $remitGroups,
      'selectGroup' => $selectGroup,
      'cities' => $cities,
      'selectCity' => $selectCity,
      'branchs' => $branchs,
      'selectStatus' => $selectStatus,
      'collection' => $collection
    ]);
  }

  public function edit(Request $request, $id) {
    return $this->create($request, $id);
  }

  public function storeCollections(Request $request)
  {
    $company = Corporation::findOrFail($request->corpID);
    $rules = [];
    $niceNames = [];

    foreach($request->collections as $index => $collection) {
      $min = intval($collection['Start_CRR']) + 1;
      $rules["collections.{$index}.End_CRR"] = "numeric|min:{$min}|nullable";
      $rules["collections.{$index}.Collection"] = "numeric|nullable";

      $niceNames["collections.{$index}.End_CRR"] = 'Input';
      $niceNames["collections.{$index}.Collection"] = 'Input';
    }
    $this->validate($request, $rules, [], $niceNames);

    $collection = new \App\RemittanceCollection;
    $collection->setConnection($company->database_name);
    $collection->CreatedAt = date('Y-m-d h:i:s');
    $collection->TellerID = \Auth::user()->UserID;
    $collection->Status = 0;
    $collection->save();

    $subTotal = 0;

    foreach($request->collections as $key => $detail) {
      if($detail['End_CRR'] || $detail['Collection']) {
        $collection->details()->create($detail);
        $subTotal += $detail['Collection'];
      }
    }

    $collection->update(['Subtotal' => $subTotal]);

    \Session::flash('success', "Remittance collections has been created successfully.");
    return redirect(route('branch_remittances.index', [ 'corpID' => $request->corpID]));
  }

  public function update(Request $request, $id) {
    $company = Corporation::findOrFail($request->corpID);
    $collection = new \App\RemittanceCollection;
    $collection->setConnection($company->database_name);
    $collection = $collection->findOrFail($id);

    foreach($request->collections as $key => $detail) {
      if($detail['End_CRR'] || $detail['Collection']) {
        $collection->details()->updateOrCreate(['ID' => $detail['ID']], $detail);
      }
    }

    $subTotal = 0;

    foreach($collection->details()->get() as $detail) {
      $subTotal += $detail->Collection;
    }

    $collection->update(['Subtotal' => $subTotal]);

    \Session::flash('success', "Remittance collections has been created successfully.");
    return redirect(route('branch_remittances.index', [ 'corpID' => $request->corpID]));
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

  public function destroy(Request $request, $id){
    $company = Corporation::findOrFail($request->corpID);
    $collectionModel = new \App\RemittanceCollection;
    $collectionModel->setConnection($company->database_name);

    $collection = $collectionModel->findOrFail($id);
    $collection->delete();

    \Session::flash('success', "Remittance collections has been deleted successfully.");
    return redirect(route('branch_remittances.index', [ 'corpID' => $request->corpID]));
  }
}
