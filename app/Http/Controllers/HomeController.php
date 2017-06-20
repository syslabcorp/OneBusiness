<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Request;
use Nexmo;
use DB;
use URL;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$base_url = URL::to('/biomertic-login');
		$userId = Auth::id();
		$users = DB::table('sysusers')->where('UserID', $userId)->first();
		$finger_exist = DB::table('demo_finger')->where('user_id', $userId)->count();
		if($finger_exist || $users->bio_auth == 0){
			$data['btn'] = "";
		}else{
			$url_register = base64_encode($base_url."/register.php?user_id=".$users->UserID);
			$data['btn'] = "<a href='finspot:FingerspotReg;$url_register' class='user-finger btn btn-primary' onclick=\"user_register('".$users->UserID."','".$users->Username."')\" finger-count = '$finger_exist'>Register</a>";
			//$data['btn'] = "<a href='javascript:;' class='user-finger btn btn-primary' onclick=\"user_register('".$users->UserID."','".$users->Username."')\" finger-count = '$finger_exist'>Register</a>";
		}
		
		//echo "ok"; die;
		/* Nexmo::message()->send([
			'to' => '917906500917',
			'from' => 'NEXMO',
			'text' => 'Using the facade to send a mesage.'
		]); */

		/* $message = "Twilio Testing Message";
		Twilio::message('+917906500917', $message); */
		
		/* Twilio::call('+917906500917', function ($message) {
			$message->say('Hello');
			$message->play('https://api.twilio.com/cowbell.mp3', ['loop' => 5]);
		}); */
        return view('home', $data);
    }
	
	
	public function home_ajax_action(){
		if(Request::isMethod('post')){
			$base_url = URL::to('/biomertic-login');
			$formData = Request::all();
			switch($formData['action']){
				case 'check_btn':
					$users = DB::table('sysusers')->where('Username', $formData['username'])->first();
					if(!empty($users) && $users->otp_auth == 0 && $users->bio_auth == 1){
						$finger_exist = DB::table('demo_finger')->where('user_id', $users->UserID)->count();
						$response['id'] = $users->UserID;
						$response['finger_exist'] = $finger_exist;
						if($finger_exist){
							$url_verification = base64_encode($base_url."/verification.php?user_id=".$users->UserID);
							$response['btn'] = "<a href='finspot:FingerspotVer;$url_verification' class='btn btn-success'>Login</a>";
						}else{
							$url_register = base64_encode($base_url."/register.php?user_id=".$users->UserID);
							$response['btn'] = "<a href='finspot:FingerspotReg;$url_register' class='user-finger btn btn-primary' onclick=\"user_register('".$users->UserID."','".$users->Username."')\" finger-count = '$finger_exist'>Register</a>";
						}
					}else{
						$response['id'] = 0;
					}
					echo json_encode($response);
				break;
				
				case 'checkreg':
					$finger_count = DB::table('demo_finger')->where('user_id', $formData['user_id'])->count();
					if (intval($finger_count) > intval($formData['current'])) {
						$response['result'] = true;			
						$response['current'] = intval($finger_count);			
					}else{
						$response['result'] = false;
					}
					echo json_encode($response);
				break;
				
				case 'btnontype':
					$users = DB::table('sysusers')->where('Username', $formData['username'])->first();
					$finger_exist = DB::table('demo_finger')->where('user_id', $users->UserID)->count();
					$response['id'] = $users->UserID;
					$response['finger_exist'] = $finger_exist;
					if($finger_exist){
						$url_verification = base64_encode($base_url."/verification.php?user_id=".$users->UserID);
						$response['btn'] = "<a href='finspot:FingerspotVer;$url_verification' class='btn btn-success'>Login</a>";
					}else{
						$url_register = base64_encode($base_url."/register.php?user_id=".$users->UserID);
						$response['btn'] = "<a href='finspot:FingerspotReg;$url_register' class='user-finger btn btn-primary' onclick=\"user_register_type('".$users->UserID."','".$users->Username."')\" finger-count = '$finger_exist'>Register</a>";
					}
					echo json_encode($response);
				break;
			
				case 'remove_finger':
					DB::table('demo_finger')->where('user_id', $formData['user_id'])->delete();
					$users = DB::table('sysusers')->where('UserID', $formData['user_id'])->first();
					$url_register = base64_encode($base_url."/register.php?user_id=".$users->UserID);
					$response['btn'] = "<a href='finspot:FingerspotReg;$url_register' class='user-finger btn btn-xs btn-primary' onclick=\"user_register_admin('".$users->UserID."','".$users->Username."')\" finger-count = '0'>Register</a>";
					echo json_encode($response);
				break;
			}
		}
	}
	
	public function get_logout(){
		Request::session()->flash('flash_message', 'Registration successful, Please login to continue.');
		Request::Session()->flash('alert-class', 'alert-success');
		Auth::logout();
		return redirect()->intended('username');
	}
	
	public function user_list(){
		if (Request::isMethod('post')) {
			$formData = Request::all();
			print_r($formData); die;
		}
		$data['users'] = $this->getUser();
		foreach($data['users'] AS $key=>$users){
			$data['users'][$key]['finger_count'] = count($this->getUserFinger($users['user_id']));
		}
		return view('login.user_list', $data);
	}
	
	function getUser() {
		$finger_count = DB::table('sysusers')->where('bio_auth', 1)->orderBy('Username')->get();
		foreach ($finger_count AS $fingercount) {
			$arr[] = array(
				'user_id'	=> $fingercount->UserID,
				'user_name'	=> $fingercount->Username
			);
		}
		return $arr;
	}
	
	function getUserFinger($user_id) {
		$finger_count = DB::table('demo_finger')->where('user_id', $user_id)->get();
		$arr = array();
		foreach($finger_count AS $fingercount) {
			$arr[] = array(
				'user_id'	=>$fingercount->user_id,
				"finger_id"	=>$fingercount->finger_id,
				"finger_data"	=>$fingercount->finger_data
				);
		}
		return $arr;
	}

	public function login_logs(){
		$data['logs_data'] = DB::table('demo_log')->whereDate('log_time', DB::raw('CURDATE()'))->get();
		$data['logtype'] =array('bio'=>'Biometric','pass'=>'Password','otp'=>'OTP');
		return view('login.login_logs',$data);
	}

	public function logout() {
		$request = Auth::user()->Username;
		$deleteloguser = DB::table('demo_log')->where('user_name', '=', $request)->delete();
	    Auth::logout();
	    return redirect('/'); 
	}
}
