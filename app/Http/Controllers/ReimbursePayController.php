<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Expense\ExpenseModel AS ExpenseDb;
use App\Http\Models\Expense\ExpenseMainModel AS ExpenseMainDb;
use App\Http\Models\AuditProcess\AuditInfoModel AS AuditInfoDb;
use App\Http\Models\Subjects\SubjectsModel AS SubjectsDb;
use App\Http\Models\Budget\BudgetSubjectDateModel AS BudgetSubjectDateDb;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Input;

class ReimbursePayController extends Common\CommonController
{
    public function index()
    {
        return view('reimbursePay.index');
    }
    
    //获取报销列表
    public function getReimbursePay(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //搜索参数
        $searchSql[] = array('exp.expense_type', '=', 'reimburse');
        $searchSql[] = array('exp.expense_cashier', '=', session('userInfo.user_id'));
        $searchSql[] = array('exp.expense_status', '=', '203');

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = ExpenseDb::from('expense AS exp')->where($searchSql)->count();
  
        //获取数据
        $result = ExpenseDb::from('expense AS exp')
            ->leftJoin('department AS dep', 'dep.dep_id','=','exp.expense_dep')
            ->leftJoin('users AS u', 'u.user_id','=','exp.expense_user')
            ->select('u.user_name AS user_name', 'dep.dep_name AS dep_name', 'exp.expense_num AS exp_num', 'exp.expense_id AS exp_id',
                'exp.expense_title AS exp_title', 'exp.expense_date AS exp_date', 'exp.expense_status AS exp_status')
            ->where($searchSql)
            ->skip($skip)
            ->take($take)
            ->orderBy('exp.expense_status', 'desc')
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
    
    //查看单据详情
    public function listReimbursePay()
    {
        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.integer' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('reimbursePay.index'));
        }
        $id = $input['id'];

        //获取单据
        $data = ExpenseDb::from('expense AS exp')
            ->leftJoin('department AS dep', 'dep.dep_id','=','exp.expense_dep')
            ->leftJoin('users AS u', 'u.user_id','=','exp.expense_user')
            ->select('u.user_name AS user_name', 'dep.dep_name AS dep_name', 'exp.expense_num', 'exp.expense_id',
                'exp.expense_title', 'exp.expense_date', 'exp.expense_status')
            ->where('exp.expense_id', $id)
            ->where('exp.expense_type', 'reimburse')
            ->where('exp.expense_cashier', session('userInfo.user_id'))
            ->get()
            ->first();
        if(!$data){
            return redirectPageMsg('-1', '单据信息获取失败，请刷新后重试', route('reimburse.index'));
        }

        //获取明细
        $data['expMain'] = ExpenseMainDb::from('expense_main AS expM')
            ->leftjoin('expense_enclosure AS expE', 'expM.exp_id', '=', 'expE.exp_id')
            ->leftjoin('subjects AS debit', 'expM.subject_id_debit', '=', 'debit.sub_id')
            ->leftjoin('subjects AS credit', 'expM.subject_id_credit', '=', 'credit.sub_id')
            ->leftjoin('budget AS budget', 'expM.budget_id', '=', 'budget.budget_id')
            ->where('expM.expense_id', $id)
            ->select('expM.exp_id', 'expM.exp_remark', 'expM.exp_amount', 'expM.enclosure', 'expE.enclo_url AS url'
                , 'debit.sub_name AS subject_debit', 'debit.sub_pid AS debit_pid'
                , 'credit.sub_name AS subject_credit', 'credit.sub_pid AS credit_pid'
                , 'budget.budget_name AS budget_name')
            ->orderBy('expM.created_at', 'asc')
            ->get()
            ->toArray();

        //获取审批进度
        $audit = AuditInfoDb::from('audit_info AS ai')
            ->leftjoin('audit_info_text AS ait', 'ai.process_id', '=', 'ait.process_id')
            ->leftjoin('users AS u', 'u.user_id', '=', 'ait.created_user')
            ->leftjoin('users_base AS ub', 'ub.user_id', '=', 'ait.created_user')
            ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->where('ai.process_type', 'reimburse')
            ->where('ai.process_app', $id)
            ->select('ait.audit_res','u.user_name', 'pos.pos_name')
            ->orderby('ait.audit_sort', 'asc')
            ->get()
            ->toArray();
        $data['audit'] = json_encode($audit);
        
        return view('reimbursePay.listReimbursePay', $data);
    }

    //更新单据信息
    public function updateExpense()
    {
        //获取post数据
        $input = Input::all();

        //过滤信息
        $rules = [
            'exp_id' => 'required|between:32,32',
            'expense' => 'required|between:32,32',
            'sub_credit' => 'required|between:32,32',
        ];
        $message = [
            'exp_id.required' => '参数不存在',
            'exp_id.between' => '参数错误',
            'expense.required' => '参数不存在',
            'expense.between' => '参数错误',
            'sub_credit.required' => '请选择科目贷',
            'sub_credit.between' => '科目贷参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('reimbursePay.listReimbursePay')."?id=".$input['expense']);
        }
        //单据号是否存在
        $expMain = ExpenseMainDb::where('exp_id', $input['exp_id'])
            ->where('expense_id', $input['expense'])
            ->select('exp_id')
            ->get()
            ->first();
        if(!$expMain){
            return redirectPageMsg('-1', '明细获取失败，请重试', route('reimbursePay.listReimbursePay')."?id=".$input['expense']);
        }

        $data['subject_id_credit'] = $input['sub_credit'];
        $result = ExpenseMainDb::where('exp_id', $input['exp_id'])
            ->where('expense_id', $input['expense'])
            ->update($data);
        if($result){
            return redirectPageMsg('1', '操作成功', route('reimbursePay.listReimbursePay').'?id='.$input['expense']);
        }else{
            return redirectPageMsg('-1', '操作失败，请重试', route('reimbursePay.listReimbursePay').'?id='.$input['expense']);
        }
    }

    //支付结果
    public function payExpense(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }
        //获取post数据
        $input = Input::all();

        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
            'res' => 'required|integer|digits_between:0,1',
            'remark_msg' => 'required_if:res,0',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.between' => '参数错误',
            'res.required' => '参数不存在',
            'res.integer' => '参数错误',
            'res.digits_between' => '参数错误',
            'remark_msg.required_if' => '请填写备注',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }

        if($input['res'] != '0' && $input['res'] != '1'){
            echoAjaxJson('-1', '状态参数错误');
        }

        if($input['res'] == '0' && !$input['remark_msg']){
            echoAjaxJson('-1', '请填写备注');
        }

        //单据号是否存在
        $exp = ExpenseDb::where('expense_id', $input['id'])
            ->where('expense_status', '203')
            ->where('expense_cashier', session('userInfo.user_id'))
            ->select('expense_id', 'expense_user', 'expense_num')
            ->get()
            ->first();
    
        if(!$exp){
            echoAjaxJson('-1', '单据获取失败，请重试');
        }

        //单据明细数据完整性
        $isNull = ExpenseMainDb::where('expense_id', $input['id'])
            ->where(function ($query) {
                $query->orWhere('budget_id', '')
                    ->orWhere('subject_id_credit', '');
            })
            ->get()
            ->first();
        if($isNull){
            echoAjaxJson('-1', '单据明细中“'.$isNull['exp_remark'].'”未选择“科目贷（付款方式）”，请补充完整后提交。');
        }
     
        //事务处理
        $result = DB::transaction(function () use($input, $exp) {
            $data['expense_status'] = $input['res'] == '1' ? '204' : '200';
            //更新状态
            ExpenseDb::where('expense_id', $input['id'])
                ->where('expense_status', '203')
                ->where('expense_cashier', session('userInfo.user_id'))
                ->select('expense_id')
                ->update($data);

            $resMsg = $input['res'] == '1' ? '出纳已付款，请确认收款。' : '出纳拒绝付款：'.$input['remark_msg'].'。';
            //发送出纳通知
            $notice['notice_id'] = getId();
            $notice['notice_app'] = $input['id'];//需要确认操作
            $notice['notice_message'] = '报销单据：编号'.$exp['expense_num'].'。'.$resMsg;
            $notice['notice_user'] = $exp['expense_user'];
            $notice['post_user'] = session('userInfo.sysConfig.reimburse.userCashier');
            $this->createNotice($notice);
            
            return true;
        });

        if($result){
            echoAjaxJson('1', '操作成功');
        }else{
            echoAjaxJson('-1', '操作失败，请刷新重试');
        }
    }
}
