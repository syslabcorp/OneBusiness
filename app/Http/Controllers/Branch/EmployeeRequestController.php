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
	public function index(EmployeeRequestHelper $employeeRequest, Request $request){
		try{
			$id = $request->corpID;
			$employeeRequest->setCorpId($id);
			$databaseName = $employeeRequest->getDatabaseName();
			$branches = Branch::where(["corp_id" => $id, "Active" => "1"])->orderBy("ShortName", "asc")->select("ShortName")->get();

			$corpType = Corporation::find($id)->corp_type;

			$corpsAndBranches = $this->generateCorpsWithBranches($corpType, $id);
			$corpsAndBranches = array_filter($corpsAndBranches, function ($item){ return count($item["branches"]) > 0; });

			return view("branchs.employeeRequest.index", ["corpId" => $id, "branches" => $branches, "corporations" => $corpsAndBranches]);
		} catch(\Exception $ex){
			return $ex->getMessage();
			// return abort(404);
		}
	}

	public function getEmployeeRequests(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$databaseName = $employeeRequest->getDatabaseName();
		$query1 = DB::select('SELECT users.UserName as users_username, users.SSS, users.PHIC, sysdata.ShortName as "from_branch", sysdata2.ShortName as "to_branch", employeeRequest.txn_no as id, employeeRequest.type, employeeRequest.date_start, employeeRequest.date_end_in as date_end, employeeRequest.UserName as request_username, employeeRequest.approved, employeeRequest.executed,employeeRequest.sex, employeeRequest.bday, employeeRequest.pagibig from '.$databaseName.'.t_cashr_rqst employeeRequest LEFT JOIN global.t_users as users ON users.UserID = employeeRequest.userid LEFT JOIN global.t_sysdata as sysdata ON employeeRequest.from_branch = sysdata.Branch LEFT JOIN global.t_sysdata as sysdata2 ON employeeRequest.to_branch = sysdata2.Branch ORDER BY DATE(employeeRequest.date_rqstd) DESC');
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
                    return '<span class="btn btn-success actionButton" '.($employeeRequest->approved == 1?"disabled":"").' data-approve-id="'.$employeeRequest->id.'" onclick="approveRequest(\''.$employeeRequest->id.'\')"><span class="glyphicon glyphicon-ok-sign"></span></span><span class="btn btn-danger actionButton" '.($employeeRequest->approved == 1?"disabled":"").' data-delete-id="'.$employeeRequest->id.'" onclick="deleteRequest(\''.$employeeRequest->id.'\', this)"><span class="glyphicon glyphicon-remove-sign"></span></span>';
                })
                ->addColumn('username', function ($employeeRequest) {
                	if($employeeRequest->type == "3") { return $employeeRequest->request_username; }
                	else { return $employeeRequest->users_username; }
                })
                ->rawColumns(['approved', "action", "executed", "date_start", "to_branch"])
                ->make('true');
	}

	public function getEmployeeRequests2(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$databaseName = $employeeRequest->getDatabaseName();
		$query1 = DB::select('SELECT users.UserName as "username", users.LastUnfrmPaid, users.Active, users.AllowedMins, users.LoginsLeft, users.SQ_Active, sysdata.ShortName as "from_branch", sysdata2.ShortName as "to_branch", employeeRequest.txn_no as id, employeeRequest.type, employeeRequest.date_start, employeeRequest.date_end_in as date_end, employeeRequest.approved, employeeRequest.executed,employeeRequest.sex from '.$databaseName.'.t_cashr_rqst employeeRequest LEFT JOIN global.t_users as users ON users.UserID = employeeRequest.userid LEFT JOIN global.t_sysdata as sysdata ON employeeRequest.from_branch = sysdata.Branch LEFT JOIN global.t_sysdata as sysdata2 ON employeeRequest.to_branch = sysdata2.Branch ORDER BY DATE(employeeRequest.date_rqstd) DESC');
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
                    return '<span class="btn btn-primary actionButton" data-reactivate-id="'.$employeeRequest->id.'" onclick="reactivateEmployee(\''.$employeeRequest->id.'\', \''.$employeeRequest->username.'\')"><span class="glyphicon glyphicon-edit"></span></span>';
                })
                ->addColumn('nx', function ($employeeRequest) {
                    return '<input disabled type="checkbox" '.($employeeRequest->Active == 1?"checked":"").'>';
                })
                ->addColumn('sq', function ($employeeRequest) {
                    return '<input disabled type="checkbox" '.($employeeRequest->SQ_Active == 1?"checked":"").'>';
                })
                ->addColumn('og', function ($employeeRequest) {
                    return '<input disabled type="checkbox" '.($employeeRequest->Active == 1?"checked":"").'>';
                })
                ->editColumn("Active", function ($query){
                	return $query->Active == 1?"Yes":"No";
                })
                // ->editColumn("LastUnfrmPaid", function ($query){
                // 	if($query->LastUnfrmPaid != "" && $query->LastUnfrmPaid != null){
                // 		$day = (new Carbon($query->LastUnfrmPaid))->format("d");
                // 		if($day > 15) return "16th";
               	// 	else { return "1st"; }
                // 	}
                // })
                ->rawColumns(["action", "nx", "sq", "og"])
                ->make('true');
	}

	public function approveEmployeeRequest(EmployeeRequestHelper $employeeRequestHelper, Request $request){
		$employeeRequestHelper->setCorpId($request->corpId);
		$employeeRequestModel = $employeeRequestHelper->getEmployeeRequestModel();
		$employeeRequest = $employeeRequestModel::where("txn_no", $request->employeeRequestId)->first();
		if($employeeRequest->type == "3"){
			$user = new User();
			$user->UserName = $employeeRequest->LastName . ", " . $employeeRequest->FirstName . " " . $employeeRequest->SuffixName; 
			$user->uname = ""; 
			$user->mobile_no = "";
			$user->email = "";
			$user->Bday = $employeeRequest->bday;
			$user->Hired = $employeeRequest->date_start;
			$user->Sex = ($employeeRequest->sex == "F"?"Female":"Male");
			$user->Position = "Attendant";
			$user->SSS = ($employeeRequest->sss != null?$employeeRequest->sss:"0");
			$user->PHIC = ($employeeRequest->phic != null?$employeeRequest->phic:"0");
			$user->Pagibig = ($employeeRequest->pagibig != null?$employeeRequest->pagibig:"0");
			$user->Rate = 0;
			$user->FullRate = 0;
			$user->PayBasis = 3;
			$user->Status_Tbl = "Z";
			$user->Level = 1;
			$user->level_id = 1;
			$user->passwrd = ($employeeRequest->pswd != null?(md5($employeeRequest->pswd)):null);
			$user->SQ_Branch = ($employeeRequest->to_branch != null?$employeeRequest->to_branch:"0");
			$user->SQ_Active = ($employeeRequest->to_branch != null?"1":"0");
			$user->LastUnfrmPaid = $this->getLastUnfrmPaid();
			$user->Branch = ($employeeRequest->to_branch != null?$employeeRequest->to_branch:"0");
			$user->Active = ($employeeRequest->to_branch != null?"1":"0");
			$user->TechActive = 0;
			$user->save();

			$t_emp_pos = $employeeRequestHelper->getT_emp_posModel();
			$t_emp_pos->level_id = "1";
			$t_emp_pos->pos_from = $employeeRequest->date_start;
			$t_emp_pos->emp_id = $user->UserID;
			$t_emp_pos->save();

			$t_emp_rate = $employeeRequestHelper->getT_emp_rateModel();
			$t_emp_rate->pay_basis = "3";
			$t_emp_rate->rate = "0";
			$t_emp_rate->emp_id = $user->UserID;
			$t_emp_rate->effect_date = $employeeRequest->date_start;
			$t_emp_rate->save();
		}
		if(!is_null($employeeRequest)) {
			$employeeRequest->approved = "1";
			$employeeRequest->save();
			return "true";
		}
		return "false";
	}

	private function getLastUnfrmPaid(){
		// if($LastUnfrmPaid != "" && $LastUnfrmPaid != null){
                	// 	$day = (Carbon::now())->format("d");
                	// 	if($day > 15) return "16th";
               		// else { return "1st"; }
                	// }
                	return null;
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

	private function generateCorpsWithBranches($corpType, $corpId){
		if($corpType == "INN") {
			$corpsAndBranches = [
				[
					"corporation" => "OG",
					"branches" => Branch::where(["corp_id" => $corpId, "Active" => "1"])->where("ShortName", "like", "%OG%")->orderBy("ShortName", "ASC")->get()
				]
			];
		}
		if($corpType == "ICAFE") {
			$corpsAndBranches = [
				[
					"corporation" => "NX",
					"branches" => Branch::where(["corp_id" => $corpId, "Active" => "1"])->where("ShortName", "like", "%NX%")->orderBy("ShortName", "ASC")->get()
				],
				[
					"corporation" => "SQ",
					"branches" => Branch::where(["corp_id" => $corpId, "Active" => "1"])->where("ShortName", "like", "%SQ%")->orderBy("ShortName", "ASC")->get()
				]
			];
		}
		return $corpsAndBranches;
	}
}
