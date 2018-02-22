<?php

namespace App\Http\Models\Branch;

use App\Corporation;

class EmployeeRequestHelper
{
	public function __construct(){

	}

	public function getRequestModelByCorpId($corpId){
		$database_name = Corporation::select("database_name")->where("corp_id", $corpId)->first();
		if(!is_null($database_name)) {
				$modelName = "App\Http\Models\Tables\\".$database_name->database_name."\EmployeeRequest";
				return new $modelName;
		}
	}
}
