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
        Route::get('addNode', ['as' => 'node.addNode', 'uses' => 'NodeController@addNode']);//添加权限视图
        Route::post('createNode', ['as' => 'node.createNode', 'uses' => 'NodeController@createNode']);//添加权限
        Route::get('editNode', ['as' => 'node.editNode', 'uses' => 'NodeController@editNode']);//编辑权限视图
        Route::post('updateNode', ['as' => 'node.updateNode', 'uses' => 'NodeController@updateNode']);//更新权限
        Route::post('delNode', ['as' => 'node.delNode', 'uses' => 'NodeController@delNode']);//删除权限
    });
    //角色列表
    Route::group(['prefix' => 'role'], function () {
        Route::get('index', ['as' => 'role.index', 'uses' => 'RoleController@index']);//角色列表
        Route::post('getRole', ['as' => 'role.getRole', 'uses' => 'RoleController@getRole']);//获取角色列表
        Route::get('addRole', ['as' => 'role.addRole', 'uses' => 'RoleController@addRole']);//添加角色视图
        Route::post('createRole', ['as' => 'role.createRole', 'uses' => 'RoleController@createRole']);//添加角色
        Route::get('editRole', ['as' => 'role.editRole', 'uses' => 'RoleController@editRole']);//编辑角色视图
        Route::post('updateRole', ['as' => 'role.updateRole', 'uses' => 'RoleController@updateRole']);//更新角色
        Route::post('delRole', ['as' => 'role.delRole', 'uses' => 'RoleController@delRole']);//删除角色
        Route::get('roleInfo', ['as' => 'role.roleInfo', 'uses' => 'RoleController@roleInfo']);//角色详情
    });
    //系统设置
    Route::group(['prefix' => 'sysConfig'], function () {
        Route::get('index', ['as' => 'sysConfig.index', 'uses' => 'SysConfigController@index']);//系统设置
        Route::post('updateBudget', ['as' => 'sysConfig.updateBudget', 'uses' => 'SysConfigController@updateBudget']);//更新预算配置
        Route::post('updateContract', ['as' => 'sysConfig.updateContract', 'uses' => 'SysConfigController@updateContract']);//更新合同配置
        Route::post('updateReimburse', ['as' => 'sysConfig.updateReimburse', 'uses' => 'SysConfigController@updateReimburse']);//更新费用管理配置
    });
    /*-----------------------------我的工作-----------------------------*/
    //消息通知
    Route::group(['prefix' => 'notice'], function () {
        Route::get('index', ['as' => 'notice.index', 'uses' => 'NoticeController@index']);//消息通知
        Route::post('getNotice', ['as' => 'notice.getNotice', 'uses' => 'NoticeController@getNotice']);//通知列表
        Route::get('noticeRead', ['as' => 'notice.noticeRead', 'uses' => 'NoticeController@noticeRead']);//阅读消息
        Route::post('updateNotice', ['as' => 'notice.updateNotice', 'uses' => 'NoticeController@updateNotice']);//添加审核结果
    });
    //流程审核
    Route::group(['prefix' => 'auditMy'], function () {
        Route::get('index', ['as' => 'auditMy.index', 'uses' => 'AuditMyController@index']);//流程审核
        Route::post('getAuditList', ['as' => 'auditMy.getAuditList', 'uses' => 'AuditMyController@getAuditList']);//获取审核列表
        Route::get('getAuditInfo', ['as' => 'auditMy.getAuditInfo', 'uses' => 'AuditMyController@getAuditInfo']);//获取审核信息
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
        Route::get('editAudit', ['as' => 'auditProcess.editAudit', 'uses' => 'AuditProcessController@editAudit']);//编辑审核流程视图
        Route::post('updateAudit', ['as' => 'auditProcess.updateAudit', 'uses' => 'AuditProcessController@updateAudit']);//更新审核流程
    });
    /*-----------------------------公司信息-----------------------------*/
    //科目管理
    Route::group(['prefix' => 'subjects'], function () {
        Route::get('index', ['as' => 'subjects.index', 'uses' => 'SubjectsController@index']);//部门列表
        Route::post('getSubjects', ['as' => 'subjects.getSubjects', 'uses' => 'SubjectsController@getSubjects']);//获取部门列表
        Route::get('addSubjects', ['as' => 'subjects.addSubjects', 'uses' => 'SubjectsController@addSubjects']);//添加部门视图
        Route::post('createSubjects', ['as' => 'subjects.createSubjects', 'uses' => 'SubjectsController@createSubjects']);//添加部门
        Route::get('editSubjects', ['as' => 'subjects.editSubjects', 'uses' => 'SubjectsController@editSubjects']);//修改部门视图
        Route::post('updateSubjects', ['as' => 'subjects.updateSubjects', 'uses' => 'SubjectsController@updateSubjects']);//更新部门
    });
    //部门列表
    Route::group(['prefix' => 'department'], function () {
        Route::get('index', ['as' => 'department.index', 'uses' => 'DepartmentController@index']);//部门列表
        Route::post('getDepartment', ['as' => 'department.getDepartment', 'uses' => 'DepartmentController@getDepartment']);//获取部门列表
        Route::get('addDepartment', ['as' => 'department.addDepartment', 'uses' => 'DepartmentController@addDepartment']);//添加部门视图
        Route::post('createDepartment', ['as' => 'department.createDepartment', 'uses' => 'DepartmentController@createDepartment']);//添加部门
        Route::get('editDepartment', ['as' => 'department.editDepartment', 'uses' => 'DepartmentController@editDepartment']);//修改部门视图
        Route::post('updateDepartment', ['as' => 'department.updateDepartment', 'uses' => 'DepartmentController@updateDepartment']);//更新部门
    });
    //岗位列表
    Route::group(['prefix' => 'positions'], function () {
        Route::get('index', ['as' => 'positions.index', 'uses' => 'PositionsController@index']);//部门列表
        Route::post('getPositions', ['as' => 'positions.getPositions', 'uses' => 'PositionsController@getPositions']);//获取部门列表
        Route::get('addPositions', ['as' => 'positions.addPositions', 'uses' => 'PositionsController@addPositions']);//添加部门视图
        Route::post('createPositions', ['as' => 'positions.createPositions', 'uses' => 'PositionsController@createPositions']);//添加部门
        Route::get('editPositions', ['as' => 'positions.editPositions', 'uses' => 'PositionsController@editPositions']);//修改部门视图
        Route::post('updatePositions', ['as' => 'positions.updatePositions', 'uses' => 'PositionsController@updatePositions']);//更新部门
     });
    //公司信息
    Route::group(['prefix' => 'company'], function () {
        Route::get('index', ['as' => 'company.index', 'uses' => 'CompanyController@index']);//员工列表
        Route::get('addCompany', ['as' => 'company.addCompany', 'uses' => 'CompanyController@addCompany']);//编辑员工视图
        Route::get('editCompany', ['as' => 'company.editCompany', 'uses' => 'CompanyController@editCompany']);//编辑员工视图
        Route::get('editCompany', ['as' => 'user.editUser', 'uses' => 'UserController@editUser']);//编辑员工视图
    });
    //员工
    Route::group(['prefix' => 'user'], function () {
        Route::get('index', ['as' => 'user.index', 'uses' => 'UserController@index']);//员工列表
        Route::post('getUser', ['as' => 'user.getUser', 'uses' => 'UserController@getUser']);//获取员工列表
        Route::get('addUser', ['as' => 'user.addUser', 'uses' => 'UserController@addUser']);//添加角色视图
        Route::post('createUser', ['as' => 'user.createUser', 'uses' => 'UserController@createUser']);//添加员工
        Route::get('editUser', ['as' => 'user.editUser', 'uses' => 'UserController@editUser']);//编辑员工视图
        Route::post('updateUser', ['as' => 'user.updateUser', 'uses' => 'UserController@updateUser']);//更新员工
        Route::get('userInfo', ['as' => 'user.userInfo', 'uses' => 'UserController@userInfo']);//个人信息
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
        Route::get('editBudget', ['as' => 'budget.editBudget', 'uses' => 'BudgetController@editBudget']);//编辑预算汇总视图
        Route::post('updateBudget', ['as' => 'budget.updateBudget', 'uses' => 'BudgetController@updateBudget']);//更新预算
        Route::get('addBudgetSub', ['as' => 'budget.addBudgetSub', 'uses' => 'BudgetController@addBudgetSub']);//添加预算项视图
        Route::post('createBudgetSub', ['as' => 'budget.createBudgetSub', 'uses' => 'BudgetController@createBudgetSub']);//添加预算项
        Route::post('getBudgetSub', ['as' => 'budget.getBudgetSub', 'uses' => 'BudgetController@getBudgetSub']);//获取预算项目
        Route::post('getBudgetDate', ['as' => 'budget.getBudgetDate', 'uses' => 'BudgetController@getBudgetDate']);//获取预算期间
        Route::get('listBudget', ['as' => 'budget.listBudget', 'uses' => 'BudgetController@listBudget']);//查看预算详情
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
        Route::get('editBudgetSum', ['as' => 'budgetSum.editBudgetSum', 'uses' => 'BudgetSumController@editBudgetSum']);//编辑预算汇总视图
        Route::post('updateBudgetSum', ['as' => 'budgetSum.updateBudgetSum', 'uses' => 'BudgetSumController@updateBudgetSum']);//更新预算汇总
        Route::get('addBudgetSumSub', ['as' => 'budgetSum.addBudgetSumSub', 'uses' => 'BudgetSumController@addBudgetSumSub']);//添加预算汇总项视图
        Route::post('createBudgetSumSub', ['as' => 'budgetSum.createBudgetSumSub', 'uses' => 'BudgetSumController@createBudgetSumSub']);//添加预算汇总项
        Route::post('getBudgetSumSub', ['as' => 'budgetSum.getBudgetSumSub', 'uses' => 'BudgetSumController@getBudgetSumSub']);//获取预算汇总项目
        Route::post('getBudgetSumDate', ['as' => 'budgetSum.getBudgetSumDate', 'uses' => 'BudgetSumController@getBudgetSumDate']);//获取预算汇总期间
        Route::get('listBudgetSum', ['as' => 'budgetSum.listBudgetSum', 'uses' => 'BudgetSumController@listBudgetSum']);//查看预算汇总详情
        Route::post('delBudgetSum', ['as' => 'budgetSum.delBudgetSum', 'uses' => 'BudgetSumController@delBudgetSum']);//删除预算汇总
        Route::post('subBudgetSum', ['as' => 'budgetSum.subBudgetSum', 'uses' => 'BudgetSumController@subBudgetSum']);//提交预算审核
        Route::post('listAuditSum', ['as' => 'budgetSum.listAuditSum', 'uses' => 'BudgetSumController@listAuditSum']);//查看审核进度
    });
    /*-----------------------------客户列表-----------------------------*/
    //客户列表
    Route::group(['prefix' => 'customer'], function () {
        Route::get('index', ['as' => 'customer.index', 'uses' => 'CustomerController@index']);//客户列表
        Route::post('getCustomer', ['as' => 'customer.getCustomer', 'uses' => 'CustomerController@getCustomer']);//获取客户
        Route::get('addCustomer', ['as' => 'customer.addCustomer', 'uses' => 'CustomerController@addCustomer']);//添加客户视图
        Route::post('createCustomer', ['as' => 'customer.createCustomer', 'uses' => 'CustomerController@createCustomer']);//添加客户
        Route::get('editCustomer', ['as' => 'customer.editCustomer', 'uses' => 'CustomerController@editCustomer']);//编辑客户视图
        Route::post('updateCustomer', ['as' => 'customer.updateCustomer', 'uses' => 'CustomerController@updateCustomer']);//更新客户
        Route::get('listCustomer', ['as' => 'customer.listCustomer', 'uses' => 'CustomerController@listCustomer']);//查看客户视图
        Route::post('delEnclo', ['as' => 'customer.delEnclo', 'uses' => 'CustomerController@delEnclo']);//删除附件
        Route::post('uploadEnclo', ['as' => 'customer.uploadEnclo', 'uses' => 'CustomerController@uploadEnclo']);//上传附件
    });
    /*-----------------------------供应商列表-----------------------------*/
    //供应商列表
    Route::group(['prefix' => 'supplier'], function () {
        Route::get('index', ['as' => 'supplier.index', 'uses' => 'SupplierController@index']);//供应商列表
        Route::post('getSupplier', ['as' => 'supplier.getSupplier', 'uses' => 'SupplierController@getSupplier']);//获取供应商
        Route::get('addSupplier', ['as' => 'supplier.addSupplier', 'uses' => 'SupplierController@addSupplier']);//添加供应商视图
        Route::post('createSupplier', ['as' => 'supplier.createSupplier', 'uses' => 'SupplierController@createSupplier']);//添加供应商
        Route::get('editSupplier', ['as' => 'supplier.editSupplier', 'uses' => 'SupplierController@editSupplier']);//编辑供应商视图
        Route::post('updateSupplier', ['as' => 'supplier.updateSupplier', 'uses' => 'SupplierController@updateSupplier']);//更新供应商
        Route::get('listSupplier', ['as' => 'supplier.listSupplier', 'uses' => 'SupplierController@listSupplier']);//查看客户视图
        Route::post('delEnclo', ['as' => 'supplier.delEnclo', 'uses' => 'SupplierController@delEnclo']);//删除附件
        Route::post('uploadEnclo', ['as' => 'supplier.uploadEnclo', 'uses' => 'SupplierController@uploadEnclo']);//上传附件
    });
    /*-----------------------------合同管理-----------------------------*/
    //合同列表
    Route::group(['prefix' => 'contract'], function () {
        Route::get('index', ['as' => 'contract.index', 'uses' => 'ContractController@index']);//合同
        Route::post('getContract', ['as' => 'contract.getContract', 'uses' => 'ContractController@getContract']);//获取合同列表
        Route::get('addContract', ['as' => 'contract.addContract', 'uses' => 'ContractController@addContract']);//添加合同视图
        Route::post('createContract', ['as' => 'contract.createContract', 'uses' => 'ContractController@createContract']);//创建合同
        Route::get('editContract', ['as' => 'contract.editContract', 'uses' => 'ContractController@editContract']);//编辑合同
        Route::post('updateContract', ['as' => 'contract.updateContract', 'uses' => 'ContractController@updateContract']);//更新合同
        Route::post('delContract', ['as' => 'contract.delContract', 'uses' => 'ContractController@delContract']);//删除合同
        Route::post('delDetails', ['as' => 'contract.delDetails', 'uses' => 'ContractController@delDetails']);//删除合同收付期间
        Route::post('delEnclo', ['as' => 'contract.delEnclo', 'uses' => 'ContractController@delEnclo']);//删除附件
        Route::post('uploadEnclo', ['as' => 'contract.uploadEnclo', 'uses' => 'ContractController@uploadEnclo']);//上传附件
        Route::post('getBudgetSub', ['as' => 'contract.getBudgetSub', 'uses' => 'ContractController@getBudgetSub']);//获取预算科目
        Route::get('listContract', ['as' => 'contract.listContract', 'uses' => 'ContractController@listContract']);//查看合同
        Route::post('addAudit', ['as' => 'contract.addAudit', 'uses' => 'ContractController@addAudit']);//提交审批
        Route::get('listReimburse', ['as' => 'contract.listReimburse', 'uses' => 'ContractController@listReimburse']);//查看单据
        Route::post('listAudit', ['as' => 'contract.listAudit', 'uses' => 'ContractController@listAudit']);//查看审核进度
    });
    //合同结算
    Route::group(['prefix' => 'contSettle'], function () {
        Route::get('index', ['as' => 'contSettle.index', 'uses' => 'ContSettleController@index']);//合同结算视图
        Route::post('getSettle', ['as' => 'contSettle.getSettle', 'uses' => 'ContSettleController@getSettle']);//合同结算列表
        Route::get('receivable', ['as' => 'contSettle.receivable', 'uses' => 'ContSettleController@receivable']);//合同应收
        Route::get('payable', ['as' => 'contSettle.payable', 'uses' => 'ContSettleController@payable']);//合同应付
        Route::get('income', ['as' => 'contSettle.income', 'uses' => 'ContSettleController@income']);//合同收入结算
        Route::get('payment', ['as' => 'contSettle.payment', 'uses' => 'ContSettleController@payment']);//合同付款结算
        Route::get('createReceivable', ['as' => 'contSettle.createReceivable', 'uses' => 'ContSettleController@createReceivable']);//生成合同应收
        Route::post('getReceivable', ['as' => 'contSettle.getReceivable', 'uses' => 'ContSettleController@getReceivable']);//合同应收列表
        Route::get('createPayable', ['as' => 'contSettle.createPayable', 'uses' => 'ContSettleController@createPayable']);//生成合同应付
        Route::post('getPayable', ['as' => 'contSettle.getPayable', 'uses' => 'ContSettleController@getPayable']);//合同应付列表
        Route::post('getIncome', ['as' => 'contSettle.getIncome', 'uses' => 'ContSettleController@getIncome']);//合同收入列表
        Route::post('createIncome', ['as' => 'contSettle.createIncome', 'uses' => 'ContSettleController@createIncome']);//合同收入结算
        Route::post('getPayment', ['as' => 'contSettle.getPayment', 'uses' => 'ContSettleController@getPayment']);//合同应付列表
        Route::post('createPayment', ['as' => 'contSettle.createPayment', 'uses' => 'ContSettleController@createPayment']);//合同应付结算
    });
    /*-----------------------------费用管理-----------------------------*/
    //费用报销
    Route::group(['prefix' => 'reimburse'], function () {
        Route::get('index', ['as' => 'reimburse.index', 'uses' => 'ReimburseController@index']);//费用报销
        Route::post('getReimburse', ['as' => 'reimburse.getReimburse', 'uses' => 'ReimburseController@getReimburse']);//获取费用报销单据列表
        Route::get('addReimburse', ['as' => 'reimburse.addReimburse', 'uses' => 'ReimburseController@addReimburse']);//添加报销单据视图
        Route::get('editReimburse', ['as' => 'reimburse.editReimburse', 'uses' => 'ReimburseController@editReimburse']);//编辑报销单据视图
        Route::post('updateExpense', ['as' => 'reimburse.updateExpense', 'uses' => 'ReimburseController@updateExpense']);//更新表头信息
        Route::post('createReimburseMain', ['as' => 'reimburse.createReimburseMain', 'uses' => 'ReimburseController@createReimburseMain']);//添加明细
        Route::post('delReimburse', ['as' => 'reimburse.delReimburse', 'uses' => 'ReimburseController@delReimburse']);//删除单据
        Route::post('delReimburseMain', ['as' => 'reimburse.delReimburseMain', 'uses' => 'ReimburseController@delReimburseMain']);//删除单据明细
        Route::post('uploadImg', ['as' => 'reimburse.uploadImg', 'uses' => 'ReimburseController@uploadImg']);//上传图片
        Route::post('addAudit', ['as' => 'reimburse.addAudit', 'uses' => 'ReimburseController@addAudit']);//提交审批
        Route::get('listReimburse', ['as' => 'reimburse.listReimburse', 'uses' => 'ReimburseController@listReimburse']);//查看单据
        Route::post('listAudit', ['as' => 'reimburse.listAudit', 'uses' => 'ReimburseController@listAudit']);//查看审核进度
        Route::post('confirmPay', ['as' => 'reimburse.confirmPay', 'uses' => 'ReimburseController@confirmPay']);//确认付款
        Route::post('getBudgetSub', ['as' => 'reimburse.getBudgetSub', 'uses' => 'ReimburseController@getBudgetSub']);//获取预算科目
        Route::post('getChe qckAmount', ['as' => 'reimburse.getCheckAmount', 'uses' => 'ReimburseController@getCheckAmount']);//是否超出预算金额
    });
    //报销付款
    Route::group(['prefix' => 'reimbursePay'], function () {
        Route::get('index', ['as' => 'reimbursePay.index', 'uses' => 'ReimbursePayController@index']);//报销付款
        Route::post('getReimbursePay', ['as' => 'reimbursePay.getReimbursePay', 'uses' => 'ReimbursePayController@getReimbursePay']);//获取报销付款单据列表
        Route::get('listReimbursePay', ['as' => 'reimbursePay.listReimbursePay', 'uses' => 'ReimbursePayController@listReimbursePay']);//查看单据
        Route::post('updateExpense', ['as' => 'reimbursePay.updateExpense', 'uses' => 'ReimbursePayController@updateExpense']);//上传图片
        Route::post('payExpense', ['as' => 'reimbursePay.payExpense', 'uses' => 'ReimbursePayController@payExpense']);//上传图片
    });
    /*-----------------------------发票管理-----------------------------*/
    Route::group(['prefix' => 'invoice'], function () {
        Route::get('index', ['as' => 'invoice.index', 'uses' => 'InvoiceController@index']);//发票列表
        Route::post('getInvoice', ['as' => 'invoice.getInvoice', 'uses' => 'InvoiceController@getInvoice']);//获取发票列表
        Route::get('addInvoice', ['as' => 'invoice.addInvoice', 'uses' => 'InvoiceController@addInvoice']);//添加发票集视图
        Route::post('createInvoice', ['as' => 'invoice.createInvoice', 'uses' => 'InvoiceController@createInvoice']);//添加发票集
        Route::post('delInvoice', ['as' => 'invoice.delInvoice', 'uses' => 'InvoiceController@delInvoice']);//删除发票
        Route::get('listInvoice', ['as' => 'invoice.listInvoice', 'uses' => 'InvoiceController@listInvoice']);//删除发票
        Route::post('getInvoiceDetails', ['as' => 'invoice.getInvoiceDetails', 'uses' => 'InvoiceController@getInvoiceDetails']);//获取发票明细列表
        Route::post('delInvoiceDetails', ['as' => 'invoice.delInvoiceDetails', 'uses' => 'InvoiceController@delInvoiceDetails']);//删除发票明细
        Route::get('addInvoiceChild', ['as' => 'invoice.addInvoiceChild', 'uses' => 'InvoiceController@addInvoiceChild']);//添加发票视图
        Route::post('createInvoiceChild', ['as' => 'invoice.createInvoiceChild', 'uses' => 'InvoiceController@createInvoiceChild']);//添加发票
    });

    /*-----------------------------开具发票-----------------------------*/
    Route::group(['prefix' => 'invoOpen'], function () {
        Route::get('index', ['as' => 'invoOpen.index', 'uses' => 'InvoOpenController@index']);//开具发票视图
        Route::post('createInvoOpen', ['as' => 'invoOpen.createInvoOpen', 'uses' => 'InvoOpenController@createInvoOpen']);//开具发票处理
    });

    /*-----------------------------签收发票-----------------------------*/
    Route::group(['prefix' => 'invoCollect'], function () {
        Route::get('index', ['as' => 'invoCollect.index', 'uses' => 'InvoCollectController@index']);//签收发票视图
        Route::post('createInvoCollect', ['as' => 'invoCollect.createInvoCollect', 'uses' => 'InvoCollectController@createInvoCollect']);//开具发票处理
    });

    /*-----------------------------预算报表-----------------------------*/
    Route::group(['prefix' => 'reportBud'], function () {
        Route::get('index', ['as' => 'reportBud.index', 'uses' => 'ReportBudController@index']);//预算报表视图
        Route::post('getReport', ['as' => 'reportBud.getReport', 'uses' => 'ReportBudController@getReport']);//获取报表
        Route::post('getReportDate', ['as' => 'reportBud.getReportDate', 'uses' => 'ReportBudController@getReportDate']);//获取报表
    });

    /*-----------------------------科目余额表-----------------------------*/
    Route::group(['prefix' => 'reportSub'], function () {
        Route::get('index', ['as' => 'reportSub.index', 'uses' => 'ReportSubController@index']);//科目余额表视图
        Route::post('getReport', ['as' => 'reportSub.getReport', 'uses' => 'ReportSubController@getReport']);//获取报表
    });

    /*-----------------------------系统组件-----------------------------*/
    Route::group(['prefix' => 'component'], function () {
        Route::get('ctRedirectMsg', ['as' => 'component.ctRedirectMsg', 'uses' => 'Common\ComponentController@ctRedirectMsg']);//中间层页面跳转
        Route::post('ctGetUser', ['as' => 'component.ctGetUser', 'uses' => 'Common\ComponentController@ctGetUser']);//用户数据
        Route::post('ctGetDep', ['as' => 'component.ctGetDep', 'uses' => 'Common\ComponentController@ctGetDep']);//部门数据
        Route::post('ctGetPos', ['as' => 'component.ctGetPos', 'uses' => 'Common\ComponentController@ctGetPos']);//岗位数据
        Route::post('ctGetBudget', ['as' => 'component.ctGetBudget', 'uses' => 'Common\ComponentController@ctGetBudget']);//预算数据
        Route::get('ctGetGetId', ['as' => 'component.ctGetGetId', 'uses' => 'Common\ComponentController@ctGetGetId']);//预算数据
        Route::post('ctGetCustomer', ['as' => 'component.ctGetCustomer', 'uses' => 'Common\ComponentController@ctGetCustomer']);//客户数据
        Route::post('ctGetSupplier', ['as' => 'component.ctGetSupplier', 'uses' => 'Common\ComponentController@ctGetSupplier']);//供应商数据
        Route::post('ajaxGetParentSub', ['as' => 'component.ajaxGetParentSub', 'uses' => 'Common\ComponentController@ajaxGetParentSub']);//获取科目父级名称
        Route::post('ajaxGetPaySub', ['as' => 'component.ajaxGetPaySub', 'uses' => 'Common\ComponentController@ajaxGetPaySub']);//获取支付方式科目
        Route::post('ctGetContract', ['as' => 'component.ctGetContract', 'uses' => 'Common\ComponentController@ctGetContract']);//获取合同列表
        Route::post('ctGetContDetails', ['as' => 'component.ctGetContDetails', 'uses' => 'Common\ComponentController@ctGetContDetails']);//获取合同详情列表
        Route::post('ctGetInvoice', ['as' => 'component.ctGetInvoice', 'uses' => 'Common\ComponentController@ctGetInvoice']);//获取发票集列表
        Route::post('ctGetInvoDetails', ['as' => 'component.ctGetInvoDetails', 'uses' => 'Common\ComponentController@ctGetInvoDetails']);//获取发票详情列表
    });

    /*-----------------------------联系人--------------------------------*/
    //联系人列表
    Route::group(['prefix' => 'contact'], function () {
        Route::get('index', ['as' => 'contact.index', 'uses' => 'ContactController@index']);//部门列表
        Route::post('getContact', ['as' => 'contact.getContact', 'uses' => 'ContactController@getContact']);//获取部门列表
        Route::get('addContact', ['as' => 'contact.addContact', 'uses' => 'ContactController@addContact']);//添加部门视图
        Route::post('createContact', ['as' => 'contact.createContact', 'uses' => 'ContactController@createContact']);//添加部门
        Route::get('editContact', ['as' => 'contact.editContact', 'uses' => 'ContactController@editContact']);//修改部门视图
        Route::post('updateContact', ['as' => 'contact.updateContact', 'uses' => 'ContactController@updateContact']);//更新部门
    });

    /*-----------------------------系统组件-----------------------------*/
    Route::group(['prefix' => 'updateData'], function () {
        Route::get('index', ['as' => 'updateData.index', 'uses' => 'UpdateDataController@index']);//中间层页面跳转
    });

});
