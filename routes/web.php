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
    /*-----------------------------后台主页-----------------------------*/
    Route::get('', ['as' => 'main.index', 'uses' => 'MainController@index']);
    Route::group(['prefix' => 'main'], function () {
        Route::post('getMainNotice', ['as' => 'main.getMainNotice', 'uses' => 'MainController@getMainNotice']);//消息通知列表
    });

    /*-----------------------------系统管理-----------------------------*/
    //权限列表
    Route::group(['prefix' => 'node'], function () {
        Route::get('index', ['as' => 'node.index', 'uses' => 'NodeController@index']);//权限列表
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
    //消息通知
    Route::group(['prefix' => 'notice'], function () {
        Route::get('index', ['as' => 'notice.index', 'uses' => 'NoticeController@index']);//消息通知
        Route::post('getNotice', ['as' => 'notice.getNotice', 'uses' => 'NoticeController@getNotice']);//通知列表
        Route::get('noticeRead/{id?}', ['as' => 'notice.noticeRead', 'uses' => 'NoticeController@noticeRead']);//阅读消息
        Route::post('updateNotice', ['as' => 'notice.updateNotice', 'uses' => 'NoticeController@updateNotice']);//添加审核结果
    });
    //流程审核
    Route::group(['prefix' => 'auditMy'], function () {
        Route::get('index', ['as' => 'auditMy.index', 'uses' => 'AuditMyController@index']);//流程审核
        Route::post('getAuditList', ['as' => 'auditMy.getAuditList', 'uses' => 'AuditMyController@getAuditList']);//获取审核列表
        Route::get('getAuditInfo/{id?}', ['as' => 'auditMy.getAuditInfo', 'uses' => 'AuditMyController@getAuditInfo']);//获取审核信息
        Route::post('createAuditRes', ['as' => 'auditMy.createAuditRes', 'uses' => 'AuditMyController@createAuditRes']);//添加审核结果
        Route::post('getAuditUsers', ['as' => 'auditMy.getAuditUsers', 'uses' => 'AuditMyController@getAuditUsers']);//获取审批流程名单
    });
    //回收站
    Route::group(['prefix' => 'recycle'], function () {
        Route::get('index', ['as' => 'recycle.index', 'uses' => 'RecycleController@index']);//回收站主视图
        Route::get('getRecycle', ['as' => 'recycle.getRecycle', 'uses' => 'RecycleController@getRecycle']);//回收站列表
    });
    /*-----------------------------流程控制-----------------------------*/
    //审核流程
    Route::group(['prefix' => 'auditProcess'], function () {
        Route::get('index', ['as' => 'auditProcess.index', 'uses' => 'AuditProcessController@index']);//审核流程
        Route::post('getAudit', ['as' => 'auditProcess.getAudit', 'uses' => 'AuditProcessController@getAudit']);//获取审核流程列表
        Route::get('addAudit', ['as' => 'auditProcess.addAudit', 'uses' => 'AuditProcessController@addAudit']);//添加审核流程视图
        Route::post('createAudit', ['as' => 'auditProcess.createAudit', 'uses' => 'AuditProcessController@createAudit']);//创建审核流程
        Route::post('auditInfo', ['as' => 'auditProcess.auditInfo', 'uses' => 'AuditProcessController@auditInfo']);//审核流程详情
        Route::get('editAudit/{id?}', ['as' => 'auditProcess.editAudit', 'uses' => 'AuditProcessController@editAudit']);//编辑审核流程视图
        Route::post('updateAudit', ['as' => 'auditProcess.updateAudit', 'uses' => 'AuditProcessController@updateAudit']);//更新审核流程
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
        Route::get('editBudget/{id?}', ['as' => 'budget.editBudget', 'uses' => 'BudgetController@editBudget']);//编辑预算汇总视图
        Route::post('updateBudget', ['as' => 'budget.updateBudget', 'uses' => 'BudgetController@updateBudget']);//更新预算
        Route::get('addBudgetSub/{id?}', ['as' => 'budget.addBudgetSub', 'uses' => 'BudgetController@addBudgetSub']);//添加预算项视图
        Route::post('createBudgetSub', ['as' => 'budget.createBudgetSub', 'uses' => 'BudgetController@createBudgetSub']);//添加预算项
        Route::post('getBudgetSub', ['as' => 'budget.getBudgetSub', 'uses' => 'BudgetController@getBudgetSub']);//获取预算项目
        Route::post('getBudgetDate', ['as' => 'budget.getBudgetDate', 'uses' => 'BudgetController@getBudgetDate']);//获取预算期间
        Route::get('listBudget/{id?}', ['as' => 'budget.listBudget', 'uses' => 'BudgetController@listBudget']);//查看预算详情
        Route::post('delBudget', ['as' => 'budget.delBudget', 'uses' => 'BudgetController@delBudget']);//删除预算
        Route::post('subBudget', ['as' => 'budget.subBudget', 'uses' => 'BudgetController@subBudget']);//提交预算审核
        Route::post('listAudit', ['as' => 'budget.listAudit', 'uses' => 'BudgetController@listAudit']);//查看审核进度
    });
    //预算汇总
    Route::group(['prefix' => 'budgetSum'], function () {
        Route::get('index', ['as' => 'budgetSum.index', 'uses' => 'BudgetSumController@index']);//预算汇总列表
        Route::post('getBudgetSum', ['as' => 'budgetSum.getBudgetSum', 'uses' => 'BudgetSumController@getBudgetSum']);//获取汇总预算列表
        Route::get('addBudgetSum', ['as' => 'budgetSum.addBudgetSum', 'uses' => 'BudgetSumController@addBudgetSum']);//添加预算汇总视图
        Route::post('createBudgetSum', ['as' => 'budgetSum.createBudgetSum', 'uses' => 'BudgetSumController@createBudgetSum']);//添加预算汇总
        Route::get('editBudgetSum/{id?}', ['as' => 'budgetSum.editBudgetSum', 'uses' => 'BudgetSumController@editBudgetSum']);//编辑预算汇总视图
        Route::post('updateBudgetSum', ['as' => 'budgetSum.updateBudgetSum', 'uses' => 'BudgetSumController@updateBudgetSum']);//更新预算汇总
        Route::get('addBudgetSumSub/{id?}', ['as' => 'budgetSum.addBudgetSumSub', 'uses' => 'BudgetSumController@addBudgetSumSub']);//添加预算汇总项视图
        Route::post('createBudgetSumSub', ['as' => 'budgetSum.createBudgetSumSub', 'uses' => 'BudgetSumController@createBudgetSumSub']);//添加预算汇总项
        Route::post('getBudgetSumSub', ['as' => 'budgetSum.getBudgetSumSub', 'uses' => 'BudgetSumController@getBudgetSumSub']);//获取预算汇总项目
        Route::post('getBudgetSumDate', ['as' => 'budgetSum.getBudgetSumDate', 'uses' => 'BudgetSumController@getBudgetSumDate']);//获取预算汇总期间
        Route::get('listBudgetSum/{id?}', ['as' => 'budgetSum.listBudgetSum', 'uses' => 'BudgetSumController@listBudgetSum']);//查看预算汇总详情
        Route::post('delBudgetSum', ['as' => 'budgetSum.delBudgetSum', 'uses' => 'BudgetSumController@delBudgetSum']);//删除预算汇总
        Route::post('subBudgetSum', ['as' => 'budgetSum.subBudgetSum', 'uses' => 'BudgetSumController@subBudgetSum']);//提交预算审核
        Route::post('listAuditSum', ['as' => 'budgetSum.listAuditSum', 'uses' => 'BudgetSumController@listAuditSum']);//查看审核进度
    });

    /*-----------------------------费用管理-----------------------------*/
    Route::group(['prefix' => 'reimburse'], function () {
        Route::get('index', ['as' => 'reimburse.index', 'uses' => 'ReimburseController@index']);//费用报销
        Route::post('getReimburse', ['as' => 'reimburse.getReimburse', 'uses' => 'ReimburseController@getReimburse']);//获取费用报销单据列表
        Route::get('addReimburse', ['as' => 'reimburse.addReimburse', 'uses' => 'ReimburseController@addReimburse']);//添加报销单据视图
        Route::get('editReimburse/{id?}', ['as' => 'reimburse.editReimburse', 'uses' => 'ReimburseController@editReimburse']);//编辑报销单据视图
        Route::post('updateExpense', ['as' => 'reimburse.updateExpense', 'uses' => 'ReimburseController@updateExpense']);//更新表头信息
        Route::post('createReimburseMain', ['as' => 'reimburse.createReimburseMain', 'uses' => 'ReimburseController@createReimburseMain']);//添加明细
        Route::post('delReimburse', ['as' => 'reimburse.delReimburse', 'uses' => 'ReimburseController@delReimburse']);//删除单据
        Route::post('delReimburseMain', ['as' => 'reimburse.delReimburseMain', 'uses' => 'ReimburseController@delReimburseMain']);//删除单据明细
        Route::post('uploadImg', ['as' => 'reimburse.uploadImg', 'uses' => 'ReimburseController@uploadImg']);//上传图片
        Route::post('addAudit', ['as' => 'reimburse.addAudit', 'uses' => 'ReimburseController@addAudit']);//提交审批
        Route::get('listReimburse/{id?}', ['as' => 'reimburse.listReimburse', 'uses' => 'ReimburseController@listReimburse']);//查看单据
        Route::post('listAudit', ['as' => 'reimburse.listAudit', 'uses' => 'ReimburseController@listAudit']);//查看审核进度
    });


    /*-----------------------------系统组件-----------------------------*/
    Route::group(['prefix' => 'component'], function () {
        Route::get('ctRedirectMsg', ['as' => 'component.ctRedirectMsg', 'uses' => 'Common\ComponentController@ctRedirectMsg']);//中间层页面跳转
        Route::post('ctGetUser', ['as' => 'component.ctGetUser', 'uses' => 'Common\ComponentController@ctGetUser']);//用户数据
        Route::post('ctGetDep', ['as' => 'component.ctGetDep', 'uses' => 'Common\ComponentController@ctGetDep']);//部门数据
        Route::post('ctGetPos', ['as' => 'component.ctGetPos', 'uses' => 'Common\ComponentController@ctGetPos']);//岗位数据
    });
});
