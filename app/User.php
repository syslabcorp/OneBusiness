<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
	protected $table = 't_users';
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
        'UserName', 'uname','Full_Name','email', 'passwrd', 'mobile_no', 'pswd_auth', 'otp_auth', 'bio_auth',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'passwrd', 'remember_token',
    ];
}
