<?php

namespace App\Http\Controllers\Branch;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Branch\EmployeeRequestHelper;
use App\Corporation;
use App\Branch;
use App\UserArea;
use Yajra\Datatables\Datatables;
use App\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\ModuleMaster;

class EmployeeRequestController extends Controller
{

	public function index(EmployeeRequestHelper $employeeRequest, Request $request){
		if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 38, "V")) {
		  return view("branchs.employeeRequest.index", ["hasAccess" => false]);
		}
		try{
			$id = $request->corpID;
			$employeeRequest->setCorpId($id);
			$databaseName = $employeeRequest->getDatabaseName();
			$branches = Branch::where(["corp_id" => $id, "Active" => "1"])->orderBy("ShortName", "asc")->select("ShortName")->get();

			$corpType = Corporation::find($id)->corp_type;

			$corpsAndBranches = $this->generateCorpsWithBranches($corpType, $id);
			$corpsAndBranches = array_filter($corpsAndBranches, function ($item){ return count($item["branches"]) > 0; });

			return view("branchs.employeeRequest.index", ["hasAccess" => true, "corpId" => $id, "branches" => $branches, "corporations" => $corpsAndBranches]);
		} catch(\Exception $ex){
			return $ex->getMessage();
			// return abort(404);
		}
		// new pull request
	}

	public function returnNoPermission($message){
		throw new \Exception($message, 1);
	}

	public function getEmployeeRequests(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$databaseName = $employeeRequest->getDatabaseName();
		$query1 = DB::select('SELECT users.UserName as "users_username", sysdata.ShortName as "from_branch", sysdata.Branch, sysdata.City_ID, sysdata2.City_ID as City_ID2, provinces.Prov_ID, provinces2.Prov_ID as Prov_ID2, sysdata2.ShortName as "to_branch_name", employeeRequest.txn_no as id, employeeRequest.type, employeeRequest.DateApproved, employeeRequest.to_branch, employeeRequest.from_branch as from_branch_id, employeeRequest.date_start, employeeRequest.date_end_in as date_end, employeeRequest.UserName as request_username, employeeRequest.approved, users2.uname as approvedBy, employeeRequest.executed,employeeRequest.sex, employeeRequest.bday, employeeRequest.sss as SSS, employeeRequest.phic as PHIC, employeeRequest.pagibig  from '.$databaseName.'.t_cashr_rqst employeeRequest LEFT JOIN global.t_users as users ON users.UserID = employeeRequest.userid LEFT JOIN global.t_sysdata as sysdata ON employeeRequest.from_branch = sysdata.Branch LEFT JOIN global.t_sysdata as sysdata2 ON employeeRequest.to_branch = sysdata2.Branch LEFT JOIN global.t_cities as cities ON sysdata.City_ID = cities.City_ID LEFT JOIN global.t_cities as cities2 ON sysdata2.City_ID = cities2.City_ID LEFT JOIN global.t_provinces as provinces ON cities.Prov_ID = provinces.Prov_ID LEFT JOIN global.t_provinces as provinces2 ON cities2.Prov_ID = provinces2.Prov_ID LEFT JOIN global.t_users as users2 ON users2.UserID = employeeRequest.ApprovedBy ORDER BY DATE(employeeRequest.date_rqstd) DESC');
		
		// dd($query1);

		$query1 = $this->filter_results_according_access_rights($query1, $request);

		// if($request->search["value"]) { $query1 = $this->applySearchToArray($query1, $request->search["value"]); }
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
                // ->filter(function ($query) use ($request) {

                // })
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
                 ->editColumn("to_branch_name", function($employeeRequest){
                 	return '<span to_branch_id="'.$employeeRequest->id.'">'.$employeeRequest->to_branch_name.'</span>';
                })
                ->addColumn('action', function ($employeeRequest) use ($request) {
                	$printHtml = ($request->approved == "approved" || $request->approved == "uploaded") && $employeeRequest->type == "3"  ? '<span title="Print Contract"><span style="display:inline;" class="btn btn-info actionButton"><a href="printContract/'.$request->corpId.'/'.$employeeRequest->id.'" target="_blank"><span class="glyphicon glyphicon-print"> </span></a></span></span>' : "";
                    return '
                    <span title="Approve Request"><span style="display:inline;" class="btn btn-success actionButton" '.($employeeRequest->approved == 1 || !\Auth::user()->checkAccessByIdForCorp($request->corpId, 38, "E") || $request->approved != "for_approval"?"disabled":"").' data-approve-id="'.$employeeRequest->id.'" onclick="approveRequest(\''.$employeeRequest->id.'\')"><span class="glyphicon glyphicon-ok"></span></span></span>
                    <span title="Disapprove/Delete Request"><span style="display:inline;" class="btn btn-danger actionButton" '.($employeeRequest->approved == 1 || !\Auth::user()->checkAccessByIdForCorp($request->corpId, 38, "E") || $request->approved != "for_approval"?"disabled":"").' data-delete-id="'.$employeeRequest->id.'" onclick="deleteRequest(\''.$employeeRequest->id.'\', this)"><span class="glyphicon glyphicon-remove"> </span></span></span>
                    ' . $printHtml;
                })
                ->addColumn('username', function ($employeeRequest) {
                	if($employeeRequest->type == "3") { return $employeeRequest->request_username; }
                	else { return $employeeRequest->users_username; }
                })
                ->rawColumns(['approved', "action", "executed", "date_start", "to_branch_name"])
                ->make('true');
	}

	private function applySearchToArray($arr, $value){
		$arr = array_filter($arr, function ($item) use ($value){
			// return strpos($item->users_username, $value) !== false;
			return strpos($item->users_username, $value) !== false or strpos($item->request_username, $value) !== false or strpos($item->type, $value) !== false;
		});
		return $arr;
	}

	public function getEmployeeRequests2(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$databaseName = $employeeRequest->getDatabaseName();
		$query1 = DB::select('SELECT users.UserName as "username", sysdata.City_ID, cities.Prov_ID, users.UserID, users.Branch, users.SQ_Branch, users.LastUnfrmPaid, users.Active, users.AllowedMins, users.LoginsLeft, users.SQ_Active, sysdata.ShortName from global.t_users as users JOIN global.t_sysdata as sysdata ON users.SQ_Branch = sysdata.Branch or users.Branch = sysdata.Branch LEFT JOIN global.t_cities as cities ON sysdata.City_ID = cities.City_ID where sysdata.corp_id = ?', [$request->corpId]);

		$query1 = $this->filter_results_according_access_rights2($query1, $request);

		if(!is_null($request->branch_name) && $request->branch_name != "any"){
			$query1 = array_filter($query1, function ($arr) use ($request){
				return $arr->ShortName == $request->branch_name;
			});
		} else {
			$query1 = $this->removeDuplicateElementsFromArray($query1);
		}
		if(!is_null($request->isActive) && $request->isActive != "any"){
			$query1 = array_filter($query1, function ($arr) use ($request){
				if($request->branch_name == "any" && $request->isActive == 0) {
					return $arr->SQ_Active == $request->isActive && $arr->Active == $request->isActive;
				} else {
					if(stripos($request->branch_name,'SQ') !== false) {
						return $arr->SQ_Active == $request->isActive;
					} else {
						return $arr->Active == $request->isActive;
					}
				}

				// if($request->branch_name != "any" && stripos($request->branch_name,'SQ') !== false) {
				// 	return $arr->SQ_Active == $request->isActive;
				// } else {
				// 	return $arr->Active == $request->isActive;
				// }
			});
		}
            return Datatables::of($query1)
                ->addColumn('action', function ($employeeRequest) use ($request) {
                    return '<span title="Activate Employee"><span class="btn btn-primary actionButton" '.($employeeRequest->Active == 1 || $employeeRequest->SQ_Active == 1 || !\Auth::user()->checkAccessByIdForCorp($request->corpId, 38, "E")?"disabled":"").' data-reactivate-id="'.$employeeRequest->UserID.'" onclick="reactivateEmployee(\''.$employeeRequest->UserID.'\', \''.$employeeRequest->username.'\')"><span class="glyphicon glyphicon-edit"></span></span></span>';
                })
                ->addColumn('nx', function ($employeeRequest) {
                    return '<input disabled data-NX-id="'.$employeeRequest->UserID.'" type="checkbox" '.($employeeRequest->Active == 1?"checked":"").'>';
                })
                ->addColumn('sq', function ($employeeRequest) {
                    return '<input disabled data-SQ-id="'.$employeeRequest->UserID.'" type="checkbox" '.($employeeRequest->SQ_Active == 1?"checked":"").'>';
                })
                ->addColumn('og', function ($employeeRequest) {
                    return '<input disabled data-OG-id="'.$employeeRequest->UserID.'" type="checkbox" '.($employeeRequest->Active == 1?"checked":"").'>';
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

	public function filter_results_according_access_rights($query1, $request) {
		$user = \Auth::user();

		if($user->Area_type == "BR") {
			$allowed_branches = explode(",", UserArea::where("user_ID", $user->UserID)->first()->branch);
		            	$query1 =  array_filter($query1, function ($arr) use ($request, $allowed_branches){
		            	if($arr->type == 1 || $arr->type ==3) { return in_array($arr->to_branch, $allowed_branches); }
		            	if($arr->type == 2 || $arr->type ==4) { return in_array($arr->from_branch_id, $allowed_branches); }
			});
		}


		if($user->Area_type == "CT") {
			$allowed_cities = explode(",", UserArea::where("user_ID", $user->UserID)->first()->city);

            			$query1 =  array_filter($query1, function ($arr) use ($request, $allowed_cities){
				return in_array($arr->City_ID, $allowed_cities) || in_array($arr->City_ID2, $allowed_cities);
		    	});
		}

		if($user->Area_type == "PR") {
			$allowed_provincies = explode(",", UserArea::where("user_ID", $user->UserID)->first()->province);
            			$query1 =  array_filter($query1, function ($arr) use ($request, $allowed_provincies){
				return in_array($arr->Prov_ID, $allowed_provincies) || in_array($arr->Prov_ID2, $allowed_provincies);
		    	});
		}
		return $query1;
	}

	public function filter_results_according_access_rights2($query1, $request) {
		$user = \Auth::user();
		if($user->Area_type == "BR") {
			$allowed_branches = explode(",", UserArea::where("user_ID", $user->UserID)->first()->branch);
		            	$query1 =  array_filter($query1, function ($arr) use ($request, $allowed_branches){
				return in_array($arr->Branch, $allowed_branches) || in_array($arr->SQ_Branch, $allowed_branches);
			});
		}
		if($user->Area_type == "CT") {
			$allowed_cities = explode(",", UserArea::where("user_ID", $user->UserID)->first()->city);
            			$query1 =  array_filter($query1, function ($arr) use ($request, $allowed_cities){
				return in_array($arr->City_ID, $allowed_cities);
		    	});
		}
		if($user->Area_type == "PR") {
			$allowed_provincies = explode(",", UserArea::where("user_ID", $user->UserID)->first()->province);
            			$query1 =  array_filter($query1, function ($arr) use ($request, $allowed_provincies){
				return in_array($arr->Prov_ID, $allowed_provincies);
		    	});
		}
		return $query1;
	}

	public function removeDuplicateElementsFromArray($arr){
		$arr2 = [];
		foreach ($arr as $element) {
			foreach ($arr2 as $element2) {
				if($element2->UserID == $element->UserID){
					continue 2;
				}
			}
			array_push($arr2, $element);
		}
		return $arr2;
	}

	public function approveEmployeeRequest(EmployeeRequestHelper $employeeRequestHelper, Request $request){
		$employeeRequestHelper->setCorpId($request->corpId);
		$employeeRequestModel = $employeeRequestHelper->getEmployeeRequestModel();
		$employeeRequest = $employeeRequestModel::where("txn_no", $request->employeeRequestId)->first();

		if($employeeRequest->to_branch2 != null) { $branch_name = $employeeRequest->to_branch2->ShortName; }
		else { $branch_name = null; }

		if($employeeRequest->type == "2"){
			$emp_hist = $employeeRequestHelper->get_py_emp_hist_Model();
			$emp = $emp_hist::where("EmpID", $employeeRequest->userid)->where("Branch", $employeeRequest->from_branch)->first();
			if(!is_null($emp)) {
				$emp->for_qc = 1;
				$emp->save();
			}
		}

		if($employeeRequest->type == "4"){
			$employeeRequest->user()->update(array('template' => null,'template2' => null,'template3' => null,'template4' => null ));
		}
		
		if($employeeRequest->type == "3"){
			$UserName = $employeeRequest->LastName . ", " . $employeeRequest->FirstName . " " . $employeeRequest->SuffixName; 
			$user = User::where("UserName", $UserName)->first();
			if(!is_null($user)) { return "This name has a similar record in database: Duplicate entry is not allowed"; }
			$user = new User();
			$user->UserName = $UserName;
			$user->uname = ""; 
			$user->Firstname = $employeeRequest->FirstName;
			$user->MidName = $employeeRequest->MidName;
			$user->LastName = $employeeRequest->LastName;
			$user->SuffixName = $employeeRequest->SuffixName;
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
			$user->SQ_Branch = (!is_null($branch_name) && stripos($branch_name,'SQ') !== false?$employeeRequest->to_branch:"0");
			$user->SQ_Active = (!is_null($branch_name) && stripos($branch_name,'SQ') !== false?"1":"0");
			$user->Branch = (!is_null($branch_name) && !stripos($branch_name,'SQ') !== false?$employeeRequest->to_branch:"0");
			$user->Active = (!is_null($branch_name) && !stripos($branch_name,'SQ') !== false?"1":"0");
			$user->LastUnfrmPaid = null;
			$user->TechActive = 0;
			$user->save();

			$t_emp_pos = $employeeRequestHelper->getT_emp_posModel();
			$t_emp_pos->level_id = "1";
			$t_emp_pos->pos_from = $employeeRequest->date_start;
			$t_emp_pos->emp_id = $user->UserID;
			$t_emp_pos->save();

			$emp_hist = $employeeRequestHelper->get_py_emp_hist_Model();
			$emp_hist->Branch = $employeeRequest->to_branch;
			$emp_hist->EmpID = $user->UserID;
			$emp_hist->StartDate = $employeeRequest->date_start;
			$emp_hist->Last13_Date = $this->CalculateLast13_Date($employeeRequest->date_start);
			$emp_hist->save();

			$py_emp_rate = $employeeRequestHelper->get_py_emp_rate_Model();
			$py_emp_rate->txn_id = $emp_hist->txn_id;
			$py_emp_rate->wage_tmpl8_id = $employeeRequest->wage_tmpl8_id;
			$py_emp_rate->date_changed = $employeeRequest->DateApproved;
			$py_emp_rate->effect_date = $employeeRequest->date_start;
			$py_emp_rate->approval_code = $employeeRequest->txn_no;
			$py_emp_rate->apprv_type = 1;
			$py_emp_rate->save();

			$corporation_master = Corporation::where("corp_id", $request->corpId)->first();
			$h_docs = $employeeRequestHelper->get_h_docs_Model();
			$series_no = $h_docs::where("doc_no", $corporation_master->wt_doc_cat)->orderBy('series_no', 'desc')->first()->series_no + 1;
			$h_docs->series_no = $series_no;
			$h_docs->doc_no = $corporation_master->wt_doc_cat;
			$h_docs->subcat_id = $corporation_master->wt_doc_subcat;
			$h_docs->emp_id  = $user->UserID;
			$h_docs->doc_exp  = "0000-00-00";
			$h_docs->branch  = $employeeRequest->to_branch;
			$h_docs->approval_no  = $employeeRequest->txn_no;
			$h_docs->notes  = "New employee approved by: " . \Auth::user()->uname;
			$h_docs->save();

			$h_categoryModel = $employeeRequestHelper->get_h_category_Model();
			$h_category = $h_categoryModel::where("doc_no", $corporation_master->wt_doc_cat)->first();
			$h_category->series = $series_no;
			$h_category->save();
		}
		if(!is_null($employeeRequest)) {
			$employeeRequest->approved = "1";
			$employeeRequest->ApprovedBy = \Auth::user()->UserID;
			$employeeRequest->DateApproved = Carbon::now();
			$employeeRequest->userid = isset($user->UserID) ? $user->UserID : null;
			$employeeRequest->save();
			return "true";
		}
		return "Something went wrong, please contact administration";
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
		if(!\Auth::user()->checkAccessByIdForCorp($request->corpId, 38, "D")) {
			$this->returnNoPermission("You don't have a permission to delete the request");	
		}
		$employeeRequest->setCorpId($request->corpId);
		$employeeRequestModel = $employeeRequest->getEmployeeRequestModel();
		$employeeRequest = $employeeRequestModel::where("txn_no", $request->employeeRequestId)->first();
		if(!is_null($employeeRequest)) {
			$employeeRequest->delete();
			return "true";
		}
		return "false";
	}

	public function reactivateEmployeeRequest(EmployeeRequestHelper $employeeRequestHelper, Request $request){
		if(!\Auth::user()->checkAccessByIdForCorp($request->corpId, 38, "E")) {
			return ["success" => false, "msg" => "You don't have a permission to reactivate employee"];
		}
		$request->branch_id = $this->defineBranchId($request->branch_id, $request->corpId);
		$employeeRequestHelper->setCorpId($request->corpId);
		$employeeRequestModel = $employeeRequestHelper->getEmployeeRequestModel();
		$user = User::where("UserID", $request->employeeRequestId)->first();
		$branch = Branch::where("Branch", $request->branch_id)->first();
		if(!is_null($branch)) {
			$branch->Modified = 1;
			$branch->save();
		}
		if(!is_null($user)) {
			// $user->Branch = $request->branch_id;
			$employeeRequest = $employeeRequestModel::where("userid", $user->UserID)->first();
			if(!is_null($employeeRequest)) {
				$employeeRequest->date_start = $request->start_date; $employeeRequest->save(); 
			}
			$user->Hired = $request->start_date;
			$user->FullRate = "0.00";
			$user->Rate = "0.00";
			// if((new Carbon($user->LastUnfrmPaid))->diffInDays((new Carbon($request->start_date)), false) >= 255) {
			if($this->calculateDifferenceBetweenTwoDates($user->LastUnfrmPaid, $request->start_date) >= 255) {
				$user->LastUnfrmPaid = $this->CalculateLast13_Date($request->start_date);
				$deduct_mstr = $employeeRequestHelper->get_py_deduct_mstr_Model();
				$uniform = $employeeRequestHelper->get_py_uniforms_Model();
				$uniform->EmpID = $user->UserID;
				$uniform->Amount = null !== $deduct_mstr::where("ID_deduct", "3")->first() ? $deduct_mstr::where("ID_deduct", "3")->first()->total_amt : "";
				$uniform->DateIssued = $this->CalculateLast13_Date($request->start_date);
				$uniform->save();
			}
			if(!is_null($branch)) { $branch_name = $branch->ShortName; } else { $branch_name = null; $user->Branch = "-1"; }
			$user->SQ_Branch = (!is_null($branch_name) && stripos($branch_name,'SQ') !== false)?$request->branch_id:"0";
			$user->SQ_Active = (!is_null($branch_name) && stripos($branch_name,'SQ') !== false)?"1":"0";
			$user->Branch = (!is_null($branch_name) && stripos($branch_name,'SQ') === false)?$request->branch_id:"0";
			$user->Active = (!is_null($branch_name) && stripos($branch_name,'SQ') === false)?"1":"0";
			// $user->date_start = $request->start_date;
			if($request->password != ""){
				$user->passwrd = md5($request->password);
			}
			$user->save();

			$emp_hist = $employeeRequestHelper->get_py_emp_hist_Model();
			$emp_hist->Branch = $request->branch_id;
			$emp_hist->EmpID = $user->UserID;
			$emp_hist->StartDate = $request->start_date;
			$emp_hist->for_qc = 0;
			$emp_hist->Last13_Date = $this->CalculateLast13_Date($request->start_date);
			$emp_hist->save();

			$py_emp_rate = $employeeRequestHelper->get_py_emp_rate_Model();
			$py_emp_rate->txn_id = $emp_hist->txn_id;
			$py_emp_rate->wage_tmpl8_id = isset($request->positionId) ? $request->positionId : 0; 
			$py_emp_rate->effect_date = $request->start_date;
			$py_emp_rate->date_changed = Carbon::now();
			$py_emp_rate->apprv_type = null;
			$py_emp_rate->save();

			$corporation_master = Corporation::where("corp_id", $request->corpId)->first();
			$h_docs = $employeeRequestHelper->get_h_docs_Model();
			$series_no = $h_docs::where("doc_no", $corporation_master->wt_doc_cat)->orderBy('series_no', 'desc')->first()->series_no + 1;
			$h_docs->series_no = $series_no;
			$h_docs->doc_no = $corporation_master->wt_doc_cat;
			$h_docs->subcat_id = $corporation_master->wt_doc_subcat;
			$h_docs->emp_id  = $user->UserID;
			$h_docs->branch  = $request->branch_id;
			$h_docs->doc_exp  = "0000-00-00";
			$h_docs->approval_no  = isset($employeeRequest->txn_no) ? $employeeRequest->txn_no : 0;
			$h_docs->notes  = "Reactivated By: " . \Auth::user()->uname;
			$h_docs->save();

			$h_categoryModel = $employeeRequestHelper->get_h_category_Model();
			$h_category = $h_categoryModel::where("doc_no", $corporation_master->wt_doc_cat)->first();
			$h_category->series = $series_no;
			$h_category->save();
			
			return ["success" => true, "msg" => "The employee reactivated successfully"];
		}
		return ["success" => false, "msg" => "Something went wrong. Please contact administration"];
	}

	public function defineBranchId($branch_id, $corp_id){
		if($branch_id == 0) {
			$branch = Branch::where("corp_id", $corp_id)->where("isMain", "1")->first();
			if(!is_null($branch)) { $branch_id = $branch->Branch; }
		}
		return $branch_id;
	}

	public function CalculateLast13_Date($date){
		$day = (new Carbon($date))->format("d");
		$month = (new Carbon($date))->format("m");
		$year = (new Carbon($date))->format("y");

		if($day > 15) { $newDay = "16"; }
		else { $newDay = "01"; }

		return $year . "-" . $month . "-" . $newDay;
	}

	public function calculateDifferenceBetweenTwoDates($date1, $date2){
		if($date1 == null or $date1 == "") { return 256; }
		return (new Carbon($date1))->diffInDays((new Carbon($date2)), false);
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

	public function getDepartments(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$t_depts = $employeeRequest->get_t_depts_Model();
		return $t_depts::all()->pluck("department", "dept_ID");
	}

	public function getPositions(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequest->setCorpId($request->corpId);
		$wage_tmpl8_mstr = $employeeRequest->get_wage_tmpl8_mstr_Model();
		return $wage_tmpl8_mstr::where("dept_id", $request->departmentId)->where("entry_level", "1")->pluck("position", "wage_tmpl8_id");
	}

	public function printContract(EmployeeRequestHelper $employeeRequestHelper, Request $request){
		try{
			// $employeeRequestId = 2020;
			$employeeRequestId = $request->employeeRequestId;
			$employeeRequestHelper->setCorpId($request->corpId);
			$employeeRequestModel = $employeeRequestHelper->getEmployeeRequestModel();
			$employeeRequest = $employeeRequestModel::find($employeeRequestId);

			\Log::info("employeeRequest-user-UserID = " . $employeeRequest->user->UserID);
			
			$py_emp_hist_Model = $employeeRequestHelper->get_py_emp_hist_Model();
			$txn_id = $py_emp_hist_Model::where("EmpID", $employeeRequest->user->UserID)->orderBy("txn_id", "desc")->first()->txn_id;

			\Log::info("txn_id = " . $txn_id);


			$py_emp_rate_Model = $employeeRequestHelper->get_py_emp_rate_Model();
			$wage_tmpl8_id = $py_emp_rate_Model::where("txn_id", $txn_id)->first()->wage_tmpl8_id;

			\Log::info("wage_tmpl8_id = " . $wage_tmpl8_id);

			
			$wage_tmpl8_mstr_Model = $employeeRequestHelper->get_wage_tmpl8_mstr_Model();
			$contract = $wage_tmpl8_mstr_Model::where("wage_tmpl8_id", $wage_tmpl8_id)->first();

			\Log::info("contract = " . $contract->contract);

			$dom = new \DOMDocument();
			// $dom->loadHTML($contract->contract);


			$dom->loadHTML('<html><head><style type="text/css">@page {margin: 1in;}</style></head><body>' . $contract->contract . '</body></html>');


			$p = $dom->getElementsByTagName('p');
			$index = 1;
			foreach ($p as $value) {
				if($index == 1) {
					$value->nodeValue = "";
					$startDate = (new Carbon($employeeRequest->date_start))->toDateString();
					$value->appendChild($dom->createCDATASection ('<div style=""><span>Employee: <span style="text-decoration:underline;">'.$employeeRequest->user->UserName.'</span></span>    <span style="float:right; margin-right: 100px; width:200px;">Start of Contract: <span style="text-decoration:underline;">'.$startDate.'</span></span></div>'));
				}
				if($index == 2) {
					$value->nodeValue = "";
					$value->appendChild($dom->createCDATASection ('<div style=""><span>Address: <span style="text-decoration:underline;">'.$employeeRequest->user->Address.'</span></span>    <span style="float:right; margin-right: 100px; width:200px;">Branch: <span style="text-decoration:underline;">'.$employeeRequest->to_branch2->ShortName.'</span></span></div>'));
				}
				$index++;
			}
			$pdf = \App::make('dompdf.wrapper');
			$pdf->loadHTML($dom->saveHTML());
			return $pdf->stream();
		} catch(\Exception $e){
			return response()->view('errors.4041', [], 500);
			return "Not enough information in database to generate contract for the user<br><br>ERROR MESSAGE: " . $e->getMessage();
		}
	}
}
