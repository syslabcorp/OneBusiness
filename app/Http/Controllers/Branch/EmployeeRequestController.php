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
		// $employeeRequestModel = $employeeRequest->getRequestModelByCorpId($id);
		// $unapprovedRequest = $employeeRequestModel::get();
		return view("branchs.employeeRequest.index");
	}

	public function getEmployeeRequests(EmployeeRequestHelper $employeeRequest)
	{
		$employeeRequestModel = $employeeRequest->getRequestModelByCorpId(7);
		$unapprovedRequest = $employeeRequestModel::get();
	    	return Datatables::of(Corporation::query())->make(true);
	}
}
