<?php

namespace App\Http\Controllers\Branch;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Branch\EmployeeRequestHelper;
use Datatables;

class EmployeeRequestController extends Controller
{
	public function index(EmployeeRequestHelper $employeeRequest, $id){
		// $employeeRequestModel = $employeeRequest->getRequestModelByCorpId($id);
		// $unapprovedRequest = $employeeRequestModel::get();
		return view("branchs.employeeRequest.index");
	}
}
