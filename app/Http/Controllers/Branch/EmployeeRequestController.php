<?php

namespace App\Http\Controllers\Branch;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Branch\EmployeeRequestHelper;
use DataTables;
use App\Corporation;

class EmployeeRequestController extends Controller
{
	public function index(EmployeeRequestHelper $employeeRequest, $id){
		return view("branchs.employeeRequest.index");
	}

	public function getEmployeeRequests(EmployeeRequestHelper $employeeRequest, Request $request)
	{
		$employeeRequestModel = $employeeRequest->getRequestModelByCorpId($request->input("corpId"));
		// $unapprovedRequest = $employeeRequestModel::with('user:UserID,uname', 'branch_from')->get();
		$unapprovedRequest = $employeeRequestModel::with('user:UserID,uname', 'from_branch:Branch,ShortName', 'to_branch:Branch,ShortName')->get();
	    	return $unapprovedRequest;
	}
}
