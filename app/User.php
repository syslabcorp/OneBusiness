<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
    ];
 
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'passwrd', 'remember_token',
    ];

    public function branchs()
    {
        return $this->hasMany(\App\Branch::class);
    }

    public function area() {
        return $this->belongsTo(\App\UserArea::class, 'UserID', 'user_ID');
    }

    public function shifts()
    {
        return $this->hasMany(\App\Shift::class, 'ShiftOwner', 'UserID');
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
   public function checkAccessById($feature_id, $action)
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
    
            if($feature_id== $permission->feature_id && preg_match("/$action/", $permission->access_type))
			{
                return true;
            }
        }

        return false;
    }


    public function checkAccessByPoId($module_ids,$feature_id, $action)
    {   
        if($this->permissions == null)
        {
			$fetch_module_ids = \DB::table('rights_detail')
					->select('rights_detail.module_id')
					->where('rights_detail.template_id', '=', \Auth::user()->rights_template_id)
					->where('rights_detail.feature_id', '=', $feature_id)
					->groupBy('rights_detail.module_id')
					->get();
			
			$all_module_ids = array();
			foreach($fetch_module_ids AS $fetch_module_id){
				array_push($all_module_ids, $fetch_module_id->module_id);
			}
			if(empty($all_module_ids)){
				return false;
			}else{
				$match_corp = \DB::table('module_masters')
					->select('module_masters.module_id')
					->whereIn('module_masters.module_id', $all_module_ids)
					->where('module_masters.corp_id', '=', $module_ids[0])
					->get();
				if(isset($match_corp[0]->module_id)){
					$this->permissions = \DB::table('rights_detail')
						->select('rights_detail.module_id','rights_detail.template_id','rights_detail.feature_id','rights_detail.access_type')
						->where('rights_detail.template_id', '=', \Auth::user()->rights_template_id)
						->where('rights_detail.feature_id', '=', $feature_id)
						->where('rights_detail.module_id', '=', $match_corp[0]->module_id)
						->get();
				}
			}
        }
        foreach($this->permissions as $permission)
        {
            if($feature_id == $permission->feature_id && preg_match("/$action/", $permission->access_type))
            {
                return true;
            }
        }
        return false;
    }
}
