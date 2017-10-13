<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::any('/home_ajax_action', 'HomeController@home_ajax_action');

Route::any('/username', 'LoginController@username');
Route::any('/password', 'LoginController@password');
Route::any('/one_time_pass', 'LoginController@one_time_pass');
Route::any('/resend_otp', 'LoginController@resend_otp');
Route::any('/logintype', 'LoginController@logintype');
Route::any('/ajax_action', 'LoginController@ajax_action');

Route::get('posts/{post}/edit', 'PostController@edit');
Route::get('test', 'LoginController@test');

Route::get('/process_register/{?}', 'LoginController@process_register');
Route::get('/display_message/{?}', 'LoginController@display_message');

Route::get('/user_list', 'HomeController@user_list');
Route::get('/finger_varification/{user_id}', 'LoginController@finger_varification');
Route::get('/get_logout', 'HomeController@get_logout');
Route::any('/forgot_pass', 'LoginController@forgot_pass');
Route::any('/change_pass/{user_id?}', 'LoginController@change_pass');

Route::any('/add_corporation/{corp_id?}', 'AccessLevelController@add_corporation');
Route::get('/list_corporation', 'AccessLevelController@list_corporation');
Route::get('/delete_corporation/{corp_id}', 'AccessLevelController@destroycorporation');

Route::any('/add_module/{module_id?}', 'AccessLevelController@add_module');
Route::get('/list_module', 'AccessLevelController@list_module');
Route::get('/delete_module/{module_id}', 'AccessLevelController@destroymodule');

Route::any('/add_feature/{feature_id?}/{module_id?}', 'AccessLevelController@add_feature');
Route::get('/list_feature/{module_id?}', 'AccessLevelController@list_feature');
Route::get('/delete_feature/{feature_id}/{module_id?}', 'AccessLevelController@destroyfeature');

Route::any('/add_template/{template_id?}', 'AccessLevelController@add_template');
Route::any('/template_module', 'AccessLevelController@template_module');
Route::get('/list_template', 'AccessLevelController@list_template');
Route::get('/delete_template/{template_id}', 'AccessLevelController@destroytemplate');
Route::get('/active_users', 'HomeController@login_logs');
Route::get('/logout', 'HomeController@logout');
Route::get('/template_exist', 'AccessLevelController@template_exist');
Route::any('/add_menu/{parent_id?}/{id?}', 'AccessLevelController@add_menu');
Route::any('/list_menu/{parent_id?}', 'AccessLevelController@list_menu');
Route::get('/delete_menu/{id}', 'AccessLevelController@delete_menu');
Route::any('/get_child_menu', 'AccessLevelController@get_child_menu');
Route::any('/add_group/{id?}', 'AccessLevelController@add_group');
Route::get('/list_group', 'AccessLevelController@list_group');
Route::get('/delete_group/{id}', 'AccessLevelController@delete_group');
Route::any('/update_active_group', 'AccessLevelController@update_active_group');
Route::get('/list_user', 'AccessLevelController@list_user');
Route::any('/add_user/{id?}', 'AccessLevelController@add_user');
Route::any('/city/{user_id?}', 'AccessLevelController@city');
Route::any('/branch/{user_id?}', 'AccessLevelController@branch');
Route::any('/provinces/{user_id?}', 'AccessLevelController@provinces');
Route::get('/delete_user/{id}', 'AccessLevelController@delete_user');

Route::any('/get_provinces_ids', 'AccessLevelController@get_provinces_ids');
Route::any('/get_city_ids', 'AccessLevelController@get_city_ids');
Route::any('/get_branch_ids', 'AccessLevelController@get_branch_ids');


Route::any('/get_child_menu_call', 'AccessLevelController@get_child_menu_call');