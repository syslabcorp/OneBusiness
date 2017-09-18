<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::any('/username', 'LoginController@check_username');
Route::any('/password', 'LoginController@check_password');
Route::any('/verify_otp', 'LoginController@verify_otp');
Route::any('/send_otp', 'LoginController@send_otp');


/* Route::any('/test', function (Request $request) {
	 return response()->json(['name' => 'test']);
}); */
