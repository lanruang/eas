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


//
Route::get('login', ['as' => 'login.index', 'uses' => 'LoginController@index']);
Route::post('login', ['as' => 'login.checkLogin', 'uses' => 'LoginController@checkLogin']);
Route::get('logout', ['as' => 'login.logout', 'uses' => 'LoginController@logout']);

//页面跳转提示
Route::get('sysMessage/{status?}/{msg?}/{url?}', ['as' => 'sysMessage', 'uses' => 'SysMsgController@sysMessage']);

Route::group(['middleware' => ['permission']], function () {
    //权限列表
    Route::group(['prefix' => 'node'], function () {
        Route::get('index', ['as' => 'node.index', 'uses' => 'NodeController@index']);//权限列表
        Route::post('getNode', ['as' => 'node.getNode', 'uses' => 'NodeController@getNode']);//获取权限列表
        Route::get('addNode', ['as' => 'node.addNode', 'uses' => 'NodeController@addNode']);//添加权限视图
        Route::post('createNode', ['as' => 'node.createNode', 'uses' => 'NodeController@createNode']);//添加权限
        Route::get('editNode/{id?}', ['as' => 'node.editNode', 'uses' => 'NodeController@editNode']);//编辑权限视图
        Route::post('updateNode', ['as' => 'node.updateNode', 'uses' => 'NodeController@updateNode']);//更新权限
        Route::post('delNode', ['as' => 'node.delNode', 'uses' => 'NodeController@delNode']);//删除权限
    });
    
    //角色列表
    Route::group(['prefix' => 'role'], function () {
        Route::get('index', ['as' => 'role.index', 'uses' => 'RoleController@index']);//角色列表
        Route::post('getRole', ['as' => 'role.getRole', 'uses' => 'RoleController@getRole']);//获取角色列表
        Route::get('addRole', ['as' => 'role.addRole', 'uses' => 'RoleController@addRole']);//添加角色视图
        Route::post('createRole', ['as' => 'role.createRole', 'uses' => 'RoleController@createRole']);//添加角色
        Route::get('editRole/{id?}', ['as' => 'role.editRole', 'uses' => 'RoleController@editRole']);//编辑角色视图
        Route::post('updateRole', ['as' => 'role.updateRole', 'uses' => 'RoleController@updateRole']);//更新角色
        Route::post('delRole', ['as' => 'role.delRole', 'uses' => 'RoleController@delRole']);//删除角色
        Route::get('roleInfo/{id?}', ['as' => 'role.roleInfo', 'uses' => 'RoleController@roleInfo']);//角色详情
    });

    //公司信息
    Route::group(['prefix' => 'company'], function () {
        Route::get('index', ['as' => 'company.index', 'uses' => 'CompanyController@index']);//公司信息
        Route::get('editCompany', ['as' => 'company.editCompany', 'uses' => 'CompanyController@editCompany']);//编辑信息
        Route::post('updateCompany', ['as' => 'company.updateCompany', 'uses' => 'CompanyController@updateCompany']);//更新信息
    });

    //部门列表
    Route::group(['prefix' => 'position'], function () {
        Route::get('index', ['as' => 'position.index', 'uses' => 'PositionController@index']);//角色列表
    });

    //岗位列表
    Route::group(['prefix' => 'department'], function () {
        Route::get('index', ['as' => 'department.index', 'uses' => 'DepartmentController@index']);//角色列表
    });

    //主页
    Route::get('/', ['as' => 'main.index', 'uses' => 'MainController@index']);

    //用户
    Route::get('user', ['as' => 'user.index', 'uses' => 'UserController@index']);//用户列表
    Route::get('user/info', ['as' => 'user.info', 'uses' => 'UserController@info']);//个人信息
    Route::post('user/editPwd', ['as' => 'user.editPwd', 'uses' => 'UserController@editPwd']);//修改密码

});