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

class EmployeeRequestController extends Controller
{
	public function index(EmployeeRequestHelper $employeeRequest, $id){
		return view("branchs.employeeRequest.index");
	}

	// public function getEmployeeRequests(EmployeeRequestHelper $employeeRequest, Request $request)
	// {
	// 	$employeeRequestModel = $employeeRequest->getRequestModelByCorpId($request->input("corpId"));
	// 	// $unapprovedRequest = $employeeRequestModel::with('user:UserID,uname', 'branch_from')->get();
	// 	$unapprovedRequest = $employeeRequestModel::with('user:UserID,uname', 'from_branch:Branch,ShortName', 'to_branch:Branch,ShortName')->get();
	//     	return $unapprovedRequest;
	// }


	// public function getEmployeeRequests(EmployeeRequestHelper $employeeRequest, Request $request){
	// 	$employeeRequestModel = $employeeRequest->getRequestModelByCorpId(12);
 //            $employeeRequests = $employeeRequestModel::with('user:UserID,uname', 'from_branch:Branch,ShortName', 'to_branch:Branch,ShortName');
 //            return Datatables::of($employeeRequests)
 //                ->filter(function ($query) use ($request) {

 //                })
 //                ->addColumn('action', function ($con) {
 //                    return "<a class='btn btn-success'>See Message</a>";
 //                })
 //                ->make('true');
	// }
	
	public function getEmployeeRequests(EmployeeRequestHelper $employeeRequest, Request $request){
		$employeeRequestModel = $employeeRequest->getRequestModelByCorpId(9);
		$query1 = DB::select('SELECT users.uname as "username", sysdata.ShortName as "from_branch", sysdata2.ShortName as "to_branch" from global.t_users as users JOIN t_master.t_cashr_rqst t ON users.UserID = t.userid JOIN global.t_sysdata as sysdata ON t.from_branch = sysdata.Branch JOIN global.t_sysdata as sysdata2 ON t.to_branch = sysdata2.Branch');
            return Datatables::of($query1)
                ->filter(function ($query) use ($request) {

                })
                ->addColumn('action', function ($con) {
                    return "<a class='btn btn-success'>See Message</a>";
                })
                ->make('true');
	}
}
