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

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function() {
    Route::resource('departments', 'DepartmentsController');
    Route::resource('wage-templates', 'WageTemplatesController');
    Route::resource('equipments', 'EquipmentsController');
    Route::get('/branches/{branch}/depts', 'BranchesController@getDepts');
    Route::get('/branches/depts', 'BranchesController@getBranchesAndDepts');

    Route::resource('parts','PartsController');
    Route::resource('stocks', 'StocksController');
    Route::resource('purchase_request','PurchasesController');
});