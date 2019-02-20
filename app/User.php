<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Branch;

class User extends Authenticatable
{
    use Notifiable;

	protected $table = 't_users';

	protected $primaryKey = 'UserID';
    private $permissions;
    protected $connection = 'mysql';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    /* protected $fillable = [
        'name', 'username', 'email', 'password', 'phone', 'pswd_auth', 'otp_auth', 'bio_auth',
    ]; */
	protected $fillable = [
        'UserName', 'uname','email', 'passwrd', 'mobile_no', 'pswd_auth', 'otp_auth', 'bio_auth',
        'otp_generate_time', 'otp', 'Bday', 'Address', 'Position', 'TIN', 'Sex',
        'Bday', 'SSS', 'PHIC', 'Pagibig', 'acct_no', 'SuffixName', 'FirstName',
        'MidName', 'LastName', 'updated_at', 'split_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'passwrd', 'remember_token',
    ];

    protected $dates = [
        'Bday'
    ];

    // public function branchs()
    // {
    //     return $this->hasMany(\App\Branch::class);
    // }

    public function area() {
        return $this->belongsTo(\App\UserArea::class, 'UserID', 'user_ID');
    }

    public function shifts()
    {
        return $this->hasMany(\App\Shift::class, 'ShiftOwner', 'UserID');
    }


    public function docs() {
        return $this->hasMany(\App\HDocs::class, 'emp_id', 'UserID');
    }

    public function empHistories($db_name)
    {
        $empHistoryModel = new \App\Models\Py\EmpHistory;
        $empHistoryModel->setConnection($db_name);

        return $empHistoryModel->where('EmpID', $this->UserID);
    }

	/*
	paramete should be changed to FEATURE_ID, ACTION
	*/

    public function checkAccess($feature, $action)
    {
        if($this->permissions == null)
        {
            $this->permissions = \DB::table('rights_detail')
                ->leftJoin("feature_masters", "rights_detail.feature_id", "=", "feature_masters.feature_id")
                ->where('rights_detail.template_id', '=', \Auth::user()->rights_template_id)
                ->get();
        }

        foreach($this->permissions as $permission)
        {
            if($feature == $permission->feature && preg_match("/$action/", $permission->access_type))
            {
                return true;
            }
        }

        return false;
    }

	//this accepts (int,text), like checkAccessById(18,"A")
    public function checkAccessById($feature_id, $action) {
        if($this->permissions == null)
        {
            $this->permissions = \DB::table('rights_detail')
                ->leftJoin("feature_masters", "rights_detail.feature_id", "=", "feature_masters.feature_id")
                ->where('rights_detail.template_id', '=', \Auth::user()->rights_template_id)
                ->get();
        }

        foreach($this->permissions as $permission)
        {

            if($feature_id== $permission->feature_id && preg_match("/$action/", $permission->access_type)) {
                return true;
            }
        }

        return false;
    }

    public function checkAccessByIdForCorp($corpID, $feature_id, $action) {
      $company = \App\Company::findOrFail($corpID);

      if($this->permissions == null) {
        $this->permissions = \DB::table('rights_detail')
            ->join("module_masters", "module_masters.module_id", "=", "rights_detail.module_id")
            ->where('rights_detail.template_id', '=', \Auth::user()->rights_template_id)
            ->where('module_masters.corp_id', '=', $corpID)
            ->get();
      }

      foreach($this->permissions as $permission) {
        if($feature_id== $permission->feature_id && preg_match("/$action/", $permission->access_type)) {
          return true;
        }
      }

      return false;
    }

    public function isAdmin() {
      $template = $this->leftJoin("rights_template", "rights_template.template_id", "=", "t_users.rights_template_id")
                      ->where('rights_template.is_super_admin', '=', 1)
                      ->where('rights_template.template_id', '=', $this->rights_template_id)
                      ->first();
      return $template ? true : false;
    }


    public function checkAccessByPoId($module_ids,$feature_id, $action)
    {
        // if($this->permissions == null)
        // {
		// 	$fetch_module_ids = \DB::table('rights_detail')
		// 			->select('rights_detail.module_id')
		// 			->where('rights_detail.template_id', '=', \Auth::user()->rights_template_id)
		// 			->where('rights_detail.feature_id', '=', $feature_id)
		// 			->groupBy('rights_detail.module_id')
		// 			->get();

		// 	$all_module_ids = array();
		// 	foreach($fetch_module_ids AS $fetch_module_id){
		// 		array_push($all_module_ids, $fetch_module_id->module_id);
		// 	}
		// 	if(empty($all_module_ids)){
		// 		return false;
		// 	}else{
		// 		$match_corp = \DB::table('module_masters')
		// 			->select('module_masters.module_id')
		// 			->whereIn('module_masters.module_id', $all_module_ids)
		// 			->where('module_masters.corp_id', '=', $module_ids[0])
		// 			->get();
		// 		if(isset($match_corp[0]) && $match_corp[0]->module_id != ''){
		// 			$this->permissions = \DB::table('rights_detail')
		// 				->select('rights_detail.module_id','rights_detail.template_id','rights_detail.feature_id','rights_detail.access_type')
		// 				->where('rights_detail.template_id', '=', \Auth::user()->rights_template_id)
		// 				->where('rights_detail.feature_id', '=', $feature_id)
		// 				->where('rights_detail.module_id', '=', $match_corp[0]->module_id)
		// 				->get();
		// 		}else{
		// 			return 501;
		// 		}
		// 	}
        // }
        // foreach($this->permissions as $permission)
        // {
        //     if($feature_id == $permission->feature_id && preg_match("/$action/", $permission->access_type))
        //     {
        //         return true;
        //     }
        // }
        return true;
    }

    public function getBranchesByArea($corpID)
    {
        $branchIds = [];
        $cityIds = [];
        $provinceIds = [];

        if(\Auth::user()->area) {
          $branchIds = explode(",", \Auth::user()->area->branch);
          $cityIds = explode(",", \Auth::user()->area->city);
          $provinceIds = explode(",", \Auth::user()->area->province);
        }

        $branches = Branch::leftJoin("t_cities", "t_cities.City_ID", "=", "t_sysdata.City_ID")
                          ->orderBy('ShortName', 'ASC')
                          ->where('Active', 1)
                          ->where('corp_id', $corpID)
                          ->where(function($q) use($branchIds, $cityIds, $provinceIds) {
                            $q->orWhereIn('Branch', $branchIds)
                              ->orWhereIn('t_sysdata.City_ID', $cityIds)
                              ->orWhereIn('t_cities.Prov_ID', $provinceIds);
                          })
                          ->get();

        if ($this->level_id > 9 && !$branches->where('isMain', 1)->first()) {
            $branch = Branch::where('isMain', 1)
                                ->where('Active', 1)
                                ->where('corp_id', $corpID)
                                ->first();
            if ($branch) {
                $branches->push($branch);
            }
        }
        
        return $branches->sortBy('ShortName');
    }

    /**
     * Get Image File Name From h_docs
     * @var Integer $corpID
     * @var Integer $docNo
     * @var Integer $subcatId
     */
    public function getImageFile($corpID, $docNo, $subcatId)
    {
        $company = Corporation::find($corpID);

        $docModel = new \App\HDocs;
        $docModel->setConnection($company->database_name);

        $docItem = $docModel->where('emp_id', $this->UserID)
                            ->where('doc_no', $docNo)->where('subcat_id', $subcatId)->first();

        return $docItem ? $docItem->img_file : '';
    }

    public function getBranchesByGroup($corpID) {
       
		$groupIds = explode(",", \Auth::user()->group_ID);
        dd(\Auth::user()->group_ID);
		$remitGroups = RemitGroup::where('status', '=', 1)->whereIn('group_ID', $groupIds)->get();
		
		$branchIds = [];

		foreach ($remitGroups as $remitGroup) {
			$branchIds = array_merge($branchIds, explode(",", $remitGroup->branch));
		}

        $branches = \App\Branch::whereIn('Branch',$branchIds)
                            ->where('Active', 1)
                            ->where('corp_id', $corpID)
                            ->get();
		
		return $branches;
    }
}
