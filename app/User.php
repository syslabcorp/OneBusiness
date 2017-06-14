<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
	protected $table = 'sysusers';
	protected $primaryKey = 'UserID';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /* protected $fillable = [
        'name', 'username', 'email', 'password', 'phone', 'pswd_auth', 'otp_auth', 'bio_auth',
    ]; */
	protected $fillable = [
        'Full_Name', 'Username', 'email', 'password', 'mobile_no', 'pswd_auth', 'otp_auth', 'bio_auth',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function branchs()
    {
        return $this->hasMany(\App\Branch::class);
    }
}
