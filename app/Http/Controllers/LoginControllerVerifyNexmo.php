<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Request;
use DB;
use Twilio;
use Nexmo;
use Hash;
use Config;

class LoginController extends Controller
{
    public function username()
    {
		if (Request::isMethod('post')) {
			$formData = Request::all();
			$email = $formData['email'];
			//$users = DB::table('users')->where('email', $email)->first();
			$users = DB::table('users')->where('username', $email)->first();
			if(count($users)){
				//$data['email'] = $email;
				$data['email'] = $users->email;
				if($users->otp_auth == 1 && $users->bio_auth == 1){
					return view('login.login_type', $data);
				}else{
					$data['logintype'] = ($users->otp_auth == 1) ? 'otp_auth' : 'pswd_auth';
					return view('login.password', $data);					
				}
			}else{
				Request::session()->flash('flash_message', 'Username does not exist.');
				return redirect()->intended('username');
			}
		}
		return view('login.username');
    }
	
	public function logintype()
    {
		if (Request::isMethod('post')) {
			$formData = Request::all();
			$data['email'] = $formData['email'];
			$data['logintype'] = $formData['logintype'];
			return view('login.password', $data);
		}
		return redirect()->intended('username');
    }
	
	public function password()
    {
		if (Request::isMethod('post')) {
			$formData = Request::all();
			$email = $formData['email'];
			$password = $formData['password'];
			$users = DB::table('users')->where('email', $email)->get();
			$id = 0;
			foreach($users AS $user){
				if(Hash::check($password, $user->password)){
					$id = $user->id;
					$phone = $user->phone;
					break;
				}
			}

			if ($id) {
				$nexmo_req_err = array(1 => 'You have exceeded the submission capacity. Please wait and retry.', 2 => 'Invalid phone number.', 3 => 'Invalid phone number.', 4 => 'Their is some API credential issue.', 5 => 'Invalid phone number.', 6 => 'Application was unable to process your request.', 7 => 'The number you are trying to submit to is blacklisted and may not receive messages.', 8 => 'Application has been barred from submitting messages.', 9 => 'Application account does not have sufficient credit to process this message.', 11 => 'This account is not provisioned for REST submission, you should use SMPP instead.', 12 => 'The message length is too long.', 13 => 'Message was not submitted because there was a communication failure.', 14 => 'Message was not submitted due to a verification failure.', 29 => 'The phone number is not pre approved.', 34 => 'The phone number was either missing or invalid.');
				
				if($formData['logintype'] == "pswd_auth"){
					Auth::loginUsingId($id);
					return redirect()->intended('home');
				}else{
					$url = 'https://api.nexmo.com/verify/json?' . http_build_query([
						'api_key' => Config::get('nexmo.api_key'),
						'api_secret' => Config::get('nexmo.api_secret'),
						'number' => $phone,
						'brand' => 'Business One verification'
					]);
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$response = curl_exec($ch);
					$response = json_decode($response);
					if($response->status == 0){
						DB::table('users')->where('id', $id)->update(['otp_req_id' => $response->request_id, 'otp_generate_time' => time() ]);
					}else{
						$err_msg = isset($nexmo_req_err[$response->status]) ? $nexmo_req_err[$response->status] : 'There is some internal error, Please try after sometime.';
						Request::session()->flash('flash_message', $err_msg);
						return redirect()->intended('username');
					}
					
					/* $otp = mt_rand(1000, 9999);
					$message = "Login OTP: ".$otp;
					$response = Nexmo::message()->send([
						'to' => $phone,
						'from' => 'NEXMO',
						'text' => $message
					]); */
					//Twilio::message('+917906500917', $message);
					//DB::table('users')->where('id', $id)->update(['otp' => $otp, 'otp_generate_time' => time() ]);
					$data['user_id'] = $id;
					return view('login.one_time_pass', $data);
				}
			}else{
				$data['email'] = $email;
				$data['logintype'] = $formData['logintype'];
				Request::session()->flash('flash_message', 'Please enter correct password.');
				return view('login.password', $data);
			}
		}
		return redirect()->intended('username');
    }
	
	public function one_time_pass()
    {
		if (Request::isMethod('post')) {
			$formData = Request::all();
			$currtime = strtotime("- 5 minutes");
			//$users = DB::table('users')->where('id', $formData['user_id'])->where('otp', $formData['otp'])->first();
			$users = DB::table('users')->where('id', $formData['user_id'])->first();
			if(count($users)){
				$url = 'https://api.nexmo.com/verify/check/json?' . http_build_query([
					'api_key' => Config::get('nexmo.api_key'),
					'api_secret' => Config::get('nexmo.api_secret'),
					'request_id' => $users->otp_req_id,
					'code' => $formData['otp']
				]);

				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				$response = json_decode($response);
				if($response->status == 0){
					Auth::loginUsingId($formData['user_id']);
					return redirect()->intended('home');
				}else{
					$data['user_id'] = $formData['user_id'];
					Request::session()->flash('flash_message', $response->error_text);
					return view('login.one_time_pass', $data);
				}
				/* if($users->otp_generate_time < $currtime){
					Request::session()->flash('flash_message', 'Your OTP has been expired.');
					return redirect()->intended('username');
				}
				Auth::loginUsingId($formData['user_id']);
				return redirect()->intended('home'); */
			}else{
				$data['user_id'] = $formData['user_id'];
				Request::session()->flash('flash_message', 'Please enter correct OTP.');
				return view('login.one_time_pass', $data);
			}
		}
		return redirect()->intended('username');
    }
	
	public function resend_otp(){
		if (Request::isMethod('post')) {
			$formData = Request::all();
		}
	}
}
