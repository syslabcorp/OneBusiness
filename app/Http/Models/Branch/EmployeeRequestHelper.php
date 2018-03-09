<?php

namespace App\Http\Models\Branch;

use App\Corporation;
use \Exception;

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
		$modelName = "App\Http\Models\Tables\\".$database_name."\EmployeeRequest";
		return new $modelName;
	}

	public function getT_emp_posModel(){
		$database_name = $this->getDatabaseName();
		$modelName = "App\Http\Models\Tables\\".$database_name."\\t_emp_pos";
		return new $modelName;
	}

	public function getT_emp_rateModel(){
		$database_name = $this->getDatabaseName();
		$modelName = "App\Http\Models\Tables\\".$database_name."\\t_emp_rate";
		return new $modelName;
	}

	public function getDatabaseName(){
		if($this->corpId != "6" & $this->corpId != "7") throw new Exception("Corporation with id ".$this->corpId." is not supported yet!");
		// This database name definition should be done dynamic
		if($this->corpId == "6") { return "t_master"; }
		if($this->corpId == "7") { return "k_master"; }
		// return Corporation::select("database_name")->where("corp_id", $this->corpId)->first()->database_name;
	}
}
