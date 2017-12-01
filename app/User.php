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
            $this->permissions = \DB::table('rights_detail')
                ->leftJoin("feature_masters", "rights_detail.feature_id", "=", "feature_masters.feature_id")
                ->where('rights_detail.template_id', '=', \Auth::user()->rights_template_id)
                ->get();
        }

        foreach($this->permissions as $permission)
        {
            if(in_array($permission->module_id,$module_ids) && $feature_id == $permission->feature_id && preg_match("/$action/", $permission->access_type))
            {
                return true;
            }
        }

        return false;
    }
}
