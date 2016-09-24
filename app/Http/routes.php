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
    Route::get('user/info', ['as' => 'user.info', 'uses' => 'UserController@info']);//个人信息
    Route::post('user/editPwd', ['as' => 'user.editPwd', 'uses' => 'UserController@editPwd']);//修改密码

    //权限列表
    Route::get('permission/index', ['as' => 'permission.index', 'uses' => 'PermissionController@index']);//权限列表
    Route::post('permission/getPermission', ['as' => 'permission.getPermission', 'uses' => 'PermissionController@getPermission']);//获取权限列表
    Route::get('permission/getPermission', ['as' => 'permission.getPermission', 'uses' => 'PermissionController@getPermission']);//获取权限列表

    //角色列表
    Route::post('role/index', ['as' => 'role.index', 'uses' => 'UserController@editPwd']);//修改密码
});