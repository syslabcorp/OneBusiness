<?php

namespace App\Http\Controllers\Branch;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Branch\EmployeeRequestHelper;
use App\Corporation;
use App\Branch;
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
			$branches = Branch::where("corp_id", $id)->orderBy("ShortName", "asc")->select("ShortName")->get();

			$corpType = Corporation::find($id)->corp_type;

			$corporations = Corporation::where("corp_type", $corpType)->has("branches")->with("branches")->get();
			return view("branchs.employeeRequest.index", ["corpId" => $id, "branches" => $branches, "corporations" => $corporations]);
		} catch(\Exception $ex){
			return $ex->getMessage();
			// return abort(404);
		}
	}

	public function getEmployeeRequests(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$databaseName = $employeeRequest->getDatabaseName();
		$query1 = DB::select('SELECT employeeRequest.UserName as "username", users.SSS, users.PHIC, sysdata.ShortName as "from_branch", sysdata2.ShortName as "to_branch", employeeRequest.txn_no as id, employeeRequest.type, employeeRequest.date_start, employeeRequest.date_end_in as date_end, employeeRequest.approved, employeeRequest.executed,employeeRequest.sex, employeeRequest.bday, employeeRequest.pagibig from global.t_users as users JOIN '.$databaseName.'.t_cashr_rqst employeeRequest ON users.UserID = employeeRequest.userid LEFT JOIN global.t_sysdata as sysdata ON employeeRequest.from_branch = sysdata.Branch LEFT JOIN global.t_sysdata as sysdata2 ON employeeRequest.to_branch = sysdata2.Branch');
		// dd($query1);
		if(!is_null($request->approved) && $request->approved != "any"){
			if($request->approved == "uploaded") {
				$query1 = array_filter($query1, function ($arr){
					return $arr->executed == 1;
				});
			}
			if($request->approved == "approved") {
				$query1 = array_filter($query1, function ($arr){
					return $arr->approved == 1;
				});
			}
			if($request->approved == "for_approval") {
				$query1 = array_filter($query1, function ($arr){
					return $arr->approved == 0;
				});
			}
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
                	return '<input type=checkbox '. $checked .' disabled class="approved_td" name=' .$employeeRequest->id. '>';
                })
                 ->editColumn("executed", function($employeeRequest){
                	$checked = "";
                	if($employeeRequest->executed) { $checked = "checked"; }
                	return '<input type=checkbox '. $checked .' disabled name=' .$employeeRequest->id. '>';
                })
                 ->editColumn("date_start", function($employeeRequest){
                 	return '<span date_start_id="'.$employeeRequest->id.'">'.$employeeRequest->date_start.'</span>';
                })
                 ->editColumn("to_branch", function($employeeRequest){
                 	return '<span to_branch_id="'.$employeeRequest->id.'">'.$employeeRequest->to_branch.'</span>';
                })
                ->addColumn('action', function ($employeeRequest) {
                    // return '<img class="actionButton" data-id="'.$employeeRequest->id.'" onclick="approveRequest(\''.$employeeRequest->id.'\')" style="width:30px;" src="'.url("public/images/approve.png").'"><img class="actionButton" data-id="'.$employeeRequest->id.'" onclick="deleteRequest(\''.$employeeRequest->id.'\', this)" style="width:30px;" src="'.url("public/images/delete.png").'">';
                    return '<span class="btn btn-success actionButton" data-id="'.$employeeRequest->id.'" onclick="approveRequest(\''.$employeeRequest->id.'\')"><span class="glyphicon glyphicon-ok-sign"></span></span><span class="btn btn-danger actionButton" data-id="'.$employeeRequest->id.'" onclick="deleteRequest(\''.$employeeRequest->id.'\', this)"><span class="glyphicon glyphicon-remove-sign"></span></span>';
                })
                ->rawColumns(['approved', "action", "executed", "date_start", "to_branch"])
                ->make('true');
	}

	public function getEmployeeRequests2(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$databaseName = $employeeRequest->getDatabaseName();
		$query1 = DB::select('SELECT employeeRequest.UserName as "username", users.LastUnfrmPaid, users.Active, users.AllowedMins, users.LoginsLeft, users.SQ_Active, sysdata.ShortName as "from_branch", sysdata2.ShortName as "to_branch", employeeRequest.txn_no as id, employeeRequest.type, employeeRequest.date_start, employeeRequest.date_end_in as date_end, employeeRequest.approved, employeeRequest.executed,employeeRequest.sex from global.t_users as users JOIN '.$databaseName.'.t_cashr_rqst employeeRequest ON users.UserID = employeeRequest.userid LEFT JOIN global.t_sysdata as sysdata ON employeeRequest.from_branch = sysdata.Branch LEFT JOIN global.t_sysdata as sysdata2 ON employeeRequest.to_branch = sysdata2.Branch');
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
                    return '<span class="btn btn-primary actionButton" data-id="'.$employeeRequest->id.'" onclick="reactivateEmployee(\''.$employeeRequest->id.'\', \''.$employeeRequest->username.'\')"><span class="glyphicon glyphicon-edit"></span></span>';
                    // return '<img class="actionButton" data-id="'.$employeeRequest->id.'" onclick="reactivateEmployee(\''.$employeeRequest->id.'\', \''.$employeeRequest->username.'\')" style="width:30px;" src="'.url("public/images/activate.png").'">';
                })
                ->addColumn('nx', function ($employeeRequest) {
                    return '<input disabled type="checkbox" '.($employeeRequest->SQ_Active == 0?"checked":"").'>';
                })
                ->addColumn('sq', function ($employeeRequest) {
                    return '<input disabled type="checkbox" '.($employeeRequest->SQ_Active == 1?"checked":"").'>';
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
                ->rawColumns(["action", "nx", "sq"])
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
				$employeeRequest->user()->update(["passwrd" => md5($request->password)]);
			}
			return "true";
		}
		return "false";
	}
}
