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

    //节点列表
    Route::get('node/index', ['as' => 'node.index', 'uses' => 'NodeController@index']);//节点列表
    Route::post('node/getNode', ['as' => 'node.getNode', 'uses' => 'NodeController@getNode']);//获取节点列表
    Route::get('node/addNode', ['as' => 'node.addNode', 'uses' => 'NodeController@addNode']);//添加节点视图
    Route::post('node/createNode', ['as' => 'node.createNode', 'uses' => 'NodeController@createNode']);//添加节点
    Route::get('node/editNode/{id?}', ['as' => 'node.editNode', 'uses' => 'NodeController@editNode']);//编辑节点视图
    Route::post('node/updateNode', ['as' => 'node.updateNode', 'uses' => 'NodeController@updateNode']);//更新节点
    Route::post('node/delNode', ['as' => 'node.delNode', 'uses' => 'NodeController@delNode']);//删除节点

    //角色列表
    Route::get('role/index', ['as' => 'role.index', 'uses' => 'RoleController@index']);//角色列表
    Route::post('role/getRole', ['as' => 'role.getRole', 'uses' => 'RoleController@getRole']);//获取角色列表
    Route::get('role/addRole', ['as' => 'role.addRole', 'uses' => 'RoleController@addRole']);//添加角色视图
});