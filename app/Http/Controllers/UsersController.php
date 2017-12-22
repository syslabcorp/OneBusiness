<?php

namespace App\Http\Controllers;

use DB;
use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    $success = false;

    if($request->otp == \Auth::user()->otp) {
      $success = true;
    }

    return response()->json(['success' => $success]);
  }



}
