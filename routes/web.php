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

/*-----------------------------后台路由-----------------------------*/
Route::get('login', ['as' => 'login.index', 'uses' => 'LoginController@index']);//后台登录
Route::post('login', ['as' => 'login.checkLogin', 'uses' => 'LoginController@checkLogin']);//登录验证
Route::get('logout', ['as' => 'login.logout', 'uses' => 'LoginController@logout']);//退出登录

//验证登录中间件
Route::group(['middleware' => ['permission']], function () {
    //后台主页
    Route::get('', ['as' => 'main.index', 'uses' => 'MainController@index']);

    /*-----------------------------系统管理-----------------------------*/
    //权限列表
    Route::group(['prefix' => 'node'], function () {
        Route::get('index/', ['as' => 'node.index', 'uses' => 'NodeController@index']);//权限列表
        Route::post('getNode', ['as' => 'node.getNode', 'uses' => 'NodeController@getNode']);//获取权限列表
        Route::get('addNode/', ['as' => 'node.addNode', 'uses' => 'NodeController@addNode']);//添加权限视图
        Route::post('createNode/', ['as' => 'node.createNode', 'uses' => 'NodeController@createNode']);//添加权限
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
    /*-----------------------------我的工作-----------------------------*/
    //回收站
    Route::group(['prefix' => 'recycle'], function () {
        Route::get('index', ['as' => 'recycle.index', 'uses' => 'RecycleController@index']);//回收站主视图
        Route::get('getRecycle', ['as' => 'recycle.getRecycle', 'uses' => 'RecycleController@getRecycle']);//回收站列表
    });
    /*-----------------------------流程控制-----------------------------*/
    //审核流程
    Route::group(['prefix' => 'processAudit'], function () {
        Route::get('index', ['as' => 'processAudit.index', 'uses' => 'ProcessAuditController@index']);//审核流程
        Route::post('getAudit', ['as' => 'processAudit.getAudit', 'uses' => 'ProcessAuditController@getAudit']);//获取审核流程列表
        Route::get('addAudit', ['as' => 'processAudit.addAudit', 'uses' => 'ProcessAuditController@addAudit']);//添加审核流程视图
        Route::post('createAudit', ['as' => 'processAudit.createAudit', 'uses' => 'ProcessAuditController@createAudit']);//创建审核流程
        Route::post('auditInfo', ['as' => 'processAudit.auditInfo', 'uses' => 'ProcessAuditController@auditInfo']);//审核流程详情
        Route::get('editAudit/{id?}', ['as' => 'processAudit.editAudit', 'uses' => 'ProcessAuditController@editAudit']);//编辑审核流程视图
        Route::post('updateAudit', ['as' => 'processAudit.updateAudit', 'uses' => 'ProcessAuditController@updateAudit']);//更新审核流程
    });
    /*-----------------------------公司信息-----------------------------*/
    //科目管理
    Route::group(['prefix' => 'subjects'], function () {
        Route::get('index', ['as' => 'subjects.index', 'uses' => 'SubjectsController@index']);//部门列表
        Route::post('getSubjects', ['as' => 'subjects.getSubjects', 'uses' => 'SubjectsController@getSubjects']);//获取部门列表
        Route::get('addSubjects', ['as' => 'subjects.addSubjects', 'uses' => 'SubjectsController@addSubjects']);//添加部门视图
        Route::post('createSubjects', ['as' => 'subjects.createSubjects', 'uses' => 'SubjectsController@createSubjects']);//添加部门
        Route::get('editSubjects/{id?}', ['as' => 'subjects.editSubjects', 'uses' => 'SubjectsController@editSubjects']);//修改部门视图
        Route::post('updateSubjects', ['as' => 'subjects.updateSubjects', 'uses' => 'SubjectsController@updateSubjects']);//更新部门
    });
    //部门列表
    Route::group(['prefix' => 'department'], function () {
        Route::get('index', ['as' => 'department.index', 'uses' => 'DepartmentController@index']);//部门列表
        Route::post('getDepartment', ['as' => 'department.getDepartment', 'uses' => 'DepartmentController@getDepartment']);//获取部门列表
        Route::get('addDepartment', ['as' => 'department.addDepartment', 'uses' => 'DepartmentController@addDepartment']);//添加部门视图
        Route::post('createDepartment', ['as' => 'department.createDepartment', 'uses' => 'DepartmentController@createDepartment']);//添加部门
        Route::get('editDepartment/{id?}', ['as' => 'department.editDepartment', 'uses' => 'DepartmentController@editDepartment']);//修改部门视图
        Route::post('updateDepartment', ['as' => 'department.updateDepartment', 'uses' => 'DepartmentController@updateDepartment']);//更新部门
    });
    //岗位列表
    Route::group(['prefix' => 'positions'], function () {
        Route::get('index', ['as' => 'positions.index', 'uses' => 'PositionsController@index']);//部门列表
        Route::post('getPositions', ['as' => 'positions.getPositions', 'uses' => 'PositionsController@getPositions']);//获取部门列表
        Route::get('addPositions', ['as' => 'positions.addPositions', 'uses' => 'PositionsController@addPositions']);//添加部门视图
        Route::post('createPositions', ['as' => 'positions.createPositions', 'uses' => 'PositionsController@createPositions']);//添加部门
        Route::get('editPositions/{id?}', ['as' => 'positions.editPositions', 'uses' => 'PositionsController@editPositions']);//修改部门视图
        Route::post('updatePositions', ['as' => 'positions.updatePositions', 'uses' => 'PositionsController@updatePositions']);//更新部门
     });
    //公司信息
    Route::group(['prefix' => 'company'], function () {
        Route::get('index', ['as' => 'company.index', 'uses' => 'CompanyController@index']);//员工列表
        Route::get('addCompany', ['as' => 'company.addCompany', 'uses' => 'CompanyController@addCompany']);//编辑员工视图
        Route::get('editCompany', ['as' => 'company.editCompany', 'uses' => 'CompanyController@editCompany']);//编辑员工视图
        Route::get('editCompany/{id?}', ['as' => 'user.editUser', 'uses' => 'UserController@editUser']);//编辑员工视图
    });
    //员工
    Route::group(['prefix' => 'user'], function () {
        Route::get('index', ['as' => 'user.index', 'uses' => 'UserController@index']);//员工列表
        Route::post('getUser', ['as' => 'user.getUser', 'uses' => 'UserController@getUser']);//获取员工列表
        Route::get('addUser', ['as' => 'user.addUser', 'uses' => 'UserController@addUser']);//添加角色视图
        Route::post('createUser', ['as' => 'user.createUser', 'uses' => 'UserController@createUser']);//添加员工
        Route::get('editUser/{id?}', ['as' => 'user.editUser', 'uses' => 'UserController@editUser']);//编辑员工视图
        Route::post('updateUser', ['as' => 'user.updateUser', 'uses' => 'UserController@updateUser']);//更新员工
        Route::get('userInfo/{id?}', ['as' => 'user.userInfo', 'uses' => 'UserController@userInfo']);//个人信息
        Route::post('editPwd', ['as' => 'user.editPwd', 'uses' => 'UserController@editPwd']);//修改密码
        Route::post('delUser', ['as' => 'user.delUser', 'uses' => 'UserController@delUser']);//删除员工
        Route::post('resetPwd', ['as' => 'user.resetPwd', 'uses' => 'UserController@resetPwd']);//重置密码
    });

    /*-----------------------------预算管理-----------------------------*/
    //预算列表
    Route::group(['prefix' => 'budget'], function () {
        Route::get('index', ['as' => 'budget.index', 'uses' => 'BudgetController@index']);//预算列表
        Route::post('getBudget', ['as' => 'budget.getBudget', 'uses' => 'BudgetController@getBudget']);//获取预算列表
        Route::get('addBudget', ['as' => 'budget.addBudget', 'uses' => 'BudgetController@addBudget']);//添加预算视图
        Route::post('createBudget', ['as' => 'budget.createBudget', 'uses' => 'BudgetController@createBudget']);//添加预算
        Route::get('editBudget/{id?}', ['as' => 'budget.editBudget', 'uses' => 'BudgetController@editBudget']);//添加编辑视图
        Route::post('updateBudget', ['as' => 'budget.updateBudget', 'uses' => 'BudgetController@updateBudget']);//更新预算
        Route::get('addBudgetSub/{id?}', ['as' => 'budget.addBudgetSub', 'uses' => 'BudgetController@addBudgetSub']);//添加预算项视图
        Route::post('getBudgetSub', ['as' => 'budget.getBudgetSub', 'uses' => 'BudgetController@getBudgetSub']);//获取预算项目
        Route::post('getBudgetDate', ['as' => 'budget.getBudgetDate', 'uses' => 'BudgetController@getBudgetDate']);//获取预算期间
    });

    /*-----------------------------系统组件-----------------------------*/
    Route::group(['prefix' => 'component'], function () {
        Route::post('ctGetUser', ['as' => 'component.ctGetUser', 'uses' => 'Common\ComponentController@ctGetUser']);//用户数据
        Route::post('ctGetDep', ['as' => 'component.ctGetDep', 'uses' => 'Common\ComponentController@ctGetDep']);//部门数据
        Route::post('ctGetPos', ['as' => 'component.ctGetPos', 'uses' => 'Common\ComponentController@ctGetPos']);//岗位数据
    });
});
