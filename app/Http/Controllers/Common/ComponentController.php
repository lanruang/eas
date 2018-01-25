<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use App\Http\Models\User\UserModel AS UserDb;
use App\Http\Models\Department\DepartmentModel AS DepartmentDb;
use App\Http\Models\Positions\PositionsModel AS PositionsDb;
use App\Http\Models\Budget\BudgetModel AS BudgetDb;
use App\Http\Models\Customer\CustomerModel AS CustomerDb;
use App\Http\Models\Supplier\SupplierModel AS SupplierDb;
use App\Http\Models\Subjects\SubjectsModel AS SubjectsDb;
use App\Http\Models\Contract\ContractModel AS ContractDb;
use App\Http\Models\Contract\ContDetailsModel AS ContDetailsDb;
use Illuminate\Support\Facades\Input;
use Validator;

class ComponentController extends CommonController
{
    //中间层页面跳转
    public function ctRedirectMsg()
    {
        $input = Input::all();

        //1-正常，0-提示，-1-错误
        $result['status'] = base64_decode($input['status']);
        $result['msg'] = base64_decode($input['msg']);
        $result['url'] = base64_decode($input['url']);
        $result['btnMsg'] = isset($input['btnMsg']) ? base64_decode($input['btnMsg']) : "返回";

        return view('layouts.pageMsg', $result);
    }
    
    //获取用户
    public function ctGetUser(Request $request)
    {
        //验证传输方式
        if (!$request->ajax()) {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //搜索参数
        //$searchSql[] = array('users.supper_admin', '=', '0');
        $searchSql[] = array('users.recycle', '=', 0);
        if (array_key_exists('s_u_name', $input)) {
            $searchSql[] = array('users.user_name', 'like', '%' . $input['s_u_name'] . '%');
        }
        $data['searchSql'] = $searchSql;

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = UserDb::where($searchSql)->count();
        //获取数据
        $result = UserDb::leftJoin('users_base AS ub', 'users.user_id', '=', 'ub.user_id')
            ->leftJoin('department AS dep', 'ub.department', '=', 'dep.dep_id')
            ->leftJoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->select('users.user_id AS id', 'users.user_name AS name', 'users.user_email AS email', 'users.status',
                'dep.dep_name', 'pos.pos_name')
            ->where($searchSql)
            ->skip($skip)
            ->take($take)
            ->get()
            ->toArray();

        //创建结果数据
        $data['draw'] = isset($input['draw']) ? intval($input['draw']) : 1;
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //获取部门数据
    public function ctGetDep(Request $request)
    {
        //验证传输方式
        if (!$request->ajax()) {
            echoAjaxJson('-1', '非法请求');
        }

        $result = DepartmentDb::select('dep_id AS id', 'dep_name AS text', 'dep_pid AS pid')
            ->where('status', 1)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = DepartmentDb::where('status', 1)
            ->min('dep_pid');
        $result = !getTree($result, $selectPid) ? $result = array() : getTree($result, $selectPid);

        //返回结果
        ajaxJsonRes($result);
    }

    //获取岗位数据
    public function ctGetPos(Request $request)
    {
        //验证传输方式
        if (!$request->ajax()) {
            echoAjaxJson('-1', '非法请求');
        }

        $result = PositionsDb::select('pos_id AS id', 'pos_name AS text', 'pos_pid AS pid', 'status')
            ->orderBy('sort', 'asc')
            ->where('status', 1)
            ->get()
            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = PositionsDb::where('status', 1)
            ->min('pos_pid');
        $result = !getTree($result, $selectPid) ? $result = array() : getTree($result, $selectPid);

        //返回结果
        ajaxJsonRes($result);
    }

    //获取预算数据
    public function ctGetBudget(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //获取岗位
        $depData = DepartmentDb::select('dep_id AS id', 'dep_name AS text', 'dep_pid AS pid')
            ->where('status', 1)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        $arr = sortTree($depData, session('userInfo.dep_id'));
        foreach($arr as $v){
            $depIds[] = $v['id'];
        }
        $depIds[] = session('userInfo.dep_id');

        //检索参数
        $searchSql = array();
        $searchSql[] = array('budget.budget_sum', 0);
        $searchSql[] = array('budget.status', 1);

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度
        //获取记录总数
        $total = BudgetDb::where($searchSql)->whereIn('budget.department', $depIds)->count();
        //获取数据
        $result = BudgetDb::leftjoin('department AS dep', 'dep.dep_id', '=', 'budget.department')
            ->where($searchSql)
            ->whereIn('budget.department', $depIds)
            ->select('budget.budget_id AS id', 'budget.budget_num AS bd_num', 'budget.budget_name AS bd_name',
                'budget.budget_start AS bd_start', 'budget.budget_end AS bd_end' , 'budget.status',
                'dep.dep_name')
            ->skip($skip)
            ->take($take)
            ->orderBy('budget.status', 'Desc')
            ->get()
            ->toArray();

        //创建结果数据
        $data['draw'] = isset($input['draw']) ? intval($input['draw']) : 1;
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //获取客户数据
    public function ctGetCustomer(Request $request){
        //验证传输方式

        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = CustomerDb::count();
        //获取数据
        $result = CustomerDb::select('cust_id AS id', 'cust_num AS parties_num', 'cust_name AS parties_name')
            ->get()
            ->toArray();

        //创建结果数据
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //获取供应商数据
    public function ctGetSupplier(Request $request){
        //验证传输方式

        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = SupplierDb::count();
        //获取数据
        $result = SupplierDb::select('supp_id AS id', 'supp_num AS parties_num', 'supp_name AS parties_name')
            ->get()
            ->toArray();

        //创建结果数据
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //获取科目父级名称
    public function ajaxGetParentSub(Request $request)
    {
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'sub_pid' => 'required|between:0,32',
        ];
        $message = [
            'sub_pid.required' => '参数不存在',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            echoAjaxJson('-1', $validator->errors()->first());
        }
        
        $rel = mapKey(session('userInfo.subject'), $input['sub_pid'], 1);
        
        return ajaxJsonRes($rel);
    }

    //获取付款金额
    public function ajaxGetPaySub(Request $request)
    {
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取付款方式科目
        $subject = SubjectsDb::select('sub_id AS id', 'sub_name AS text', 'status', 'sub_pid AS pid', 'sub_ip')
            ->where('status', '1')
            ->orderBy('sub_ip', 'asc')
            ->get()
            ->toArray();
        if(!$subject){
            echoAjaxJson('-1', '付款方式科目不存在。');
        }
        $subPay = explode(',', session('userInfo.sysConfig.reimbursePay.subPay'));
        $subPaySub = array();
        foreach($subPay as $k => $v){
            $subPaySub = array_merge($subPaySub, getTree($subject, $v, '1'));
        }

        $data['status'] = 1;
        $data['data'] = $subPaySub;
        return ajaxJsonRes($data);
    }
    
    //获取合同列表
    public function ctGetContract(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = ContractDb::count();
        //获取数据
        $result = ContractDb::from('contract AS cont')
            ->leftJoin('sys_assembly AS sysAssType', 'cont.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'cont.cont_class','=','sysAssClass.ass_id')
            ->select('cont.cont_id AS id', 'cont.cont_type AS contract_type', 'cont.cont_num AS contract_num',
                'cont.cont_name AS contract_name', 'cont.cont_start AS date_start', 'cont.cont_end AS date_end',
                'cont.cont_sum_amount AS contract_amount', 'cont.cont_status AS status', 'sysAssType.ass_text AS contract_type',
                'sysAssClass.ass_text AS contract_class')
            ->get()
            ->toArray();
       
        //创建结果数据
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //获取合同详情列表
    public function ctGetContDetails()
    {
        
    }
}
