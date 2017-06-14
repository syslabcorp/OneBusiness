<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'Full_Name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:sysusers',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'Full_Name' => $data['Full_Name'],
            'Username' => $data['Username'],
            'email' => $data['email'],
            'mobile_no' => str_replace("-", "", $data['mobile_no']),
            'otp_auth' => (isset($data['otp_auth']) ? $data['otp_auth'] : 0),
            'bio_auth' => (isset($data['bio_auth']) ? $data['bio_auth'] : 0),
            //'password' => bcrypt($data['password']),
            'password' => md5($data['password']),
        ]);
    }
}
