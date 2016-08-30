<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::get('login', ['as' => 'login.index', 'uses' => 'LoginController@index']);
Route::post('login', ['as' => 'login.checkLogin', 'uses' => 'LoginController@checkLogin']);
Route::get('logout', ['as' => 'login.logout', 'uses' => 'LoginController@logout']);

Route::group(['middleware' => ['permission']], function () {
    //主页
    Route::get('/', ['as' => 'main.index', 'uses' => 'MainController@index']);
    
    //用户
    Route::get('user/index', ['as' => 'user.index', 'uses' => 'UserController@index']);//个人信息
    Route::post('user/editPwd', ['as' => 'user.editPwd', 'uses' => 'UserController@editPwd']);//修改密码
});