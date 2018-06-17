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

	public function get_t_depts_Model(){
		$database_name = $this->getDatabaseName();
		$modelName = "App\Http\Models\Tables\\".$database_name."\\t_depts";
		return new $modelName;
	}

	public function get_wage_tmpl8_mstr_Model(){
		$database_name = $this->getDatabaseName();
		$modelName = "App\Http\Models\Tables\\".$database_name."\wage_tmpl8_mstr";
		return new $modelName;
	}

	public function get_py_emp_hist_Model(){
		$database_name = $this->getDatabaseName();
		$modelName = "App\Http\Models\Tables\\".$database_name."\py_emp_hist";
		return new $modelName;
	}

	public function getT_emp_posModel(){
		$database_name = $this->getDatabaseName();
		$modelName = "App\Http\Models\Tables\\".$database_name."\\t_emp_pos";
		return new $modelName;
	}

	public function get_py_emp_rate_Model(){
		$database_name = $this->getDatabaseName();
		$modelName = "App\Http\Models\Tables\\".$database_name."\py_emp_rate";
		return new $modelName;
	}

	public function get_py_uniforms_Model(){
		$database_name = $this->getDatabaseName();
		$modelName = "App\Http\Models\Tables\\".$database_name."\py_uniforms";
		return new $modelName;
	}

	public function get_py_deduct_detail_Model(){
		$database_name = $this->getDatabaseName();
		$modelName = "App\Http\Models\Tables\\".$database_name."\py_deduct_detail";
		return new $modelName;
	}

	public function get_py_deduct_mstr_Model(){
		$database_name = $this->getDatabaseName();
		$modelName = "App\Http\Models\Tables\\".$database_name."\py_deduct_mstr";
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
