<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Request;
use App\Branch;
use DB;
use URL;



class SettingsController extends Controller
{
    //
	public function __construct()
    {
         $this->middleware('auth');
    }
	public function show_settings_page(){
		 return view('pages_settings.pg_settings');		
	}
}
