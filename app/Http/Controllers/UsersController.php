<?php

namespace App\Http\Controllers;

use DB;
use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Nexmo;

class UsersController extends Controller
{
  public function verifyPassword(Request $request) {
    $success = false;

    if(md5($request->password) == \Auth::user()->passwrd) {
      $success = true;
    }
    
    return response()->json(['success' => $success]);
  }

  public function verifyOTP(Request $request) {
    if(\Auth::user()->otp_generate_time < strtotime("- 5 minutes")){
      return response()->json([
        'success' => false,
        'message' => 'Your OTP has been expired.'
      ]);
    }

    if($request->otp != \Auth::user()->otp) {
      return response()->json([
        'success' => false,
        'message' => 'Please enter correct OTP.'
      ]);
    }

    return response()->json(['success' => $success]);
  }

  public function generateOTP(Request $request) {
    $otp = mt_rand(1000, 9999);
    $message = "Business One Collection Status OTP: " . $otp;

    $response = \Nexmo::message()->send([
      'to' => \Auth::user()->mobile_no,
      'from' => 'NEXMO',
      'text' => $message
    ]);
    if(isset($response['status']) && $response['status'] == 1){
      return response()->json([
        'success' => false,
        'message' => 'Phone number is invalid or not registered.'
      ]);
    }else {
      \Auth::user()->update(['otp' => $otp, 'otp_generate_time' => time()]);
      return response()->json([
        'success' => true
      ]);
    }
  }



}
