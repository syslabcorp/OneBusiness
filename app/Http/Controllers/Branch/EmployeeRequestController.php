<?php

namespace App\Http\Controllers\Branch;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Branch\EmployeeRequestHelper;
use App\Corporation;
use Yajra\Datatables\Datatables;
use App\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeRequestController extends Controller
{
	public function index(EmployeeRequestHelper $employeeRequest, $id){
		try{
			$employeeRequest->setCorpId($id);
			$databaseName = $employeeRequest->getDatabaseName();
			$query1 = DB::select('SELECT sysdata.ShortName as "branch", sysdata.Active from global.t_users as users JOIN '.$databaseName.'.t_cashr_rqst employeeRequest ON users.UserID = employeeRequest.userid JOIN global.t_sysdata as sysdata ON employeeRequest.from_branch = sysdata.Branch JOIN global.t_sysdata as sysdata2 ON employeeRequest.to_branch = sysdata2.Branch');

			$corpType = Corporation::find($id)->corp_type;

			$corporations = Corporation::where("corp_type", $corpType)->has("branches")->with("branches")->get();
			usort($query1, function($a,$b){ return strcmp($a->branch, $b->branch); });
			return view("branchs.employeeRequest.index", ["corpId" => $id, "branches" => $query1, "corporations" => $corporations]);
		} catch(\Exception $ex){
			return abort(404);
		}
	}

	public function getEmployeeRequests(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$databaseName = $employeeRequest->getDatabaseName();
		$query1 = DB::select('SELECT employeeRequest.UserName as "username", users.SSS, users.PHIC, sysdata.ShortName as "from_branch", sysdata2.ShortName as "to_branch", employeeRequest.txn_no as id, employeeRequest.type, employeeRequest.date_start, employeeRequest.date_end_in as date_end, employeeRequest.approved, employeeRequest.executed,employeeRequest.sex, employeeRequest.bday, employeeRequest.pagibig from global.t_users as users JOIN '.$databaseName.'.t_cashr_rqst employeeRequest ON users.UserID = employeeRequest.userid JOIN global.t_sysdata as sysdata ON employeeRequest.from_branch = sysdata.Branch JOIN global.t_sysdata as sysdata2 ON employeeRequest.to_branch = sysdata2.Branch');
		if(!is_null($request->approved) && $request->approved != "any"){
			$query1 = array_filter($query1, function ($arr) use ($request){
				return $arr->approved == $request->approved;
			});
		}

		if(!is_null($request->uploaded) && $request->uploaded != "any"){
			$query1 = array_filter($query1, function ($arr) use ($request){
				return $arr->executed == $request->uploaded;
			});
		}
            return Datatables::of($query1)
                ->filter(function ($query) use ($request) {

                })
                ->editColumn("sex", function($employeeRequest){
                	$sex = "";
                	if($employeeRequest->sex == "M") { $sex = "Male"; }
                	if($employeeRequest->sex == "F") { $sex = "Female"; }
                	return $sex;
                })
                ->editColumn("type", function($employeeRequest){
                	$type = "";
                	if($employeeRequest->type == "1") { $type = "Transfer"; }
                	if($employeeRequest->type == "2") { $type = "End of Contract"; }
                	if($employeeRequest->type == "3") { $type = "New"; }
                	if($employeeRequest->type == "4") { $type = "Re-enroll Biometric"; }
                	return $type;
                })
                ->editColumn("approved", function($employeeRequest){
                	$checked = "";
                	if($employeeRequest->approved) { $checked = "checked"; }
                	return '<input type=checkbox '. $checked .' disabled name=' .$employeeRequest->id. '>';
                })
                 ->editColumn("executed", function($employeeRequest){
                	$checked = "";
                	if($employeeRequest->executed) { $checked = "checked"; }
                	return '<input type=checkbox '. $checked .' disabled name=' .$employeeRequest->id. '>';
                })
                ->addColumn('action', function ($employeeRequest) {
                    return '<img class="actionButton" onclick="approveRequest(\''.$employeeRequest->id.'\')" style="width:30px;" src="'.url("public/images/approve.png").'"><img class="actionButton" onclick="deleteRequest(\''.$employeeRequest->id.'\', this)" style="width:30px;" src="'.url("public/images/delete.png").'">';
                })
                ->rawColumns(['approved', "action", "executed"])
                ->make('true');
	}

	public function getEmployeeRequests2(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$databaseName = $employeeRequest->getDatabaseName();
		$query1 = DB::select('SELECT employeeRequest.UserName as "username", users.LastUnfrmPaid, users.Active, users.AllowedMins, users.LoginsLeft, sysdata.ShortName as "from_branch", sysdata2.ShortName as "to_branch", employeeRequest.txn_no as id, employeeRequest.type, employeeRequest.date_start, employeeRequest.date_end_in as date_end, employeeRequest.approved, employeeRequest.executed,employeeRequest.sex from global.t_users as users JOIN '.$databaseName.'.t_cashr_rqst employeeRequest ON users.UserID = employeeRequest.userid JOIN global.t_sysdata as sysdata ON employeeRequest.from_branch = sysdata.Branch JOIN global.t_sysdata as sysdata2 ON employeeRequest.to_branch = sysdata2.Branch');
		if(!is_null($request->branch_name) && $request->branch_name != "any"){
			$query1 = array_filter($query1, function ($arr) use ($request){
				return $arr->from_branch == $request->branch_name;
			});
		}
		if(!is_null($request->isActive) && $request->isActive != "any"){
			$query1 = array_filter($query1, function ($arr) use ($request){
				return $arr->Active == $request->isActive;
			});
		}
            return Datatables::of($query1)
                ->addColumn('action', function ($employeeRequest) {
                    return '<img class="actionButton" onclick="reactivateEmployee(\''.$employeeRequest->id.'\', \''.$employeeRequest->username.'\')" style="width:30px;" src="'.url("public/images/activate.png").'">';
                })
                ->editColumn("Active", function ($query){
                	return $query->Active == 1?"Yes":"No";
                })
                ->editColumn("LastUnfrmPaid", function ($query){
                	if($query->LastUnfrmPaid != "" && $query->LastUnfrmPaid != null){
                		$day = (new Carbon($query->LastUnfrmPaid))->format("d");
                		if($day > 15) return "16th";
               		else { return "1st"; }
                	}
                })
                ->rawColumns(["action"])
                ->make('true');
	}

	public function approveEmployeeRequest(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$employeeRequestModel = $employeeRequest->getEmployeeRequestModel();
		$employeeRequest = $employeeRequestModel::where("txn_no", $request->employeeRequestId)->first();
		if(!is_null($employeeRequest)) {
			$employeeRequest->approved = "1";
			$employeeRequest->save();
			return "true";
		}
		return "false";
	}

	public function deleteEmployeeRequest(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$employeeRequestModel = $employeeRequest->getEmployeeRequestModel();
		$employeeRequest = $employeeRequestModel::where("txn_no", $request->employeeRequestId)->first();
		if(!is_null($employeeRequest)) {
			$employeeRequest->delete();
			return "true";
		}
		return "false";
	}

	public function reactivateEmployeeRequest(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$employeeRequestModel = $employeeRequest->getEmployeeRequestModel();
		$employeeRequest = $employeeRequestModel::where("txn_no", $request->employeeRequestId)->first();
		if(!is_null($employeeRequest)) {
			$employeeRequest->to_branch = $request->branch_id;
			$employeeRequest->date_start = $request->start_date;
			$employeeRequest->save();
			if($request->password != ""){
				// Password change should go here
			}
			return "true";
		}
		return "false";
	}
}