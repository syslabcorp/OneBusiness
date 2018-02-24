<?php

namespace App\Http\Models\Branch;

use App\Corporation;

class EmployeeRequestHelper
{
	private $corpId;
	public function __construct(){

	}

	public function setCorpId($corpId){
		$this->corpId = $corpId;
	}

	public function getEmployeeRequestModel(){
		$database_name = $this->getDatabaseName();
		if(!is_null($database_name)) {
				$modelName = "App\Http\Models\Tables\\".$database_name."\EmployeeRequest";
				return new $modelName;
		}
	}

	public function getDatabaseName(){
		return Corporation::select("database_name")->where("corp_id", $this->corpId)->first()->database_name;
	}
}
