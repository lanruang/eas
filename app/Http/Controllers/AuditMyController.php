<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\AuditProcess\AuditInfoModel AS AuditInfoDb;
use App\Http\Models\Budget\BudgetModel AS BudgetDb;
use App\Http\Models\Budget\BudgetSubjectModel AS BudgetSDb;
use App\Http\Models\Budget\BudgetSubjectDateModel AS BudgetSDDb;
use App\Http\Models\AuditProcess\AuditInfoTextModel AS AuditInfoTextDb;
use App\Http\Models\User\UserModel AS UserDb;
use App\Http\Models\Expense\ExpenseModel AS ExpenseDb;
use App\Http\Models\Expense\ExpenseMainModel AS ExpenseMainDb;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Contract\ContractModel AS ContractDb;
use App\Http\Models\Contract\ContDetailsModel AS ContDetailsDb;
use App\Http\Models\Contract\ContEnclosureModel AS ContEncloDb;
use App\Http\Models\Customer\CustomerModel AS CustomerDb;
use App\Http\Models\Supplier\SupplierModel AS SupplierDb;

class AuditMyController extends Common\CommonController
{
    public function index()
    {
        $data['budget'] = '';
        $data['budgetSum'] = '';
        $data['contract'] = '';
        $data['reimburse'] = '';
        //未审核数据
        $result = AuditInfoDb::where('process_audit_user', session('userInfo.user_id'))
                            ->where('status', 1000)
                            ->select(DB::raw('count(*) as num'), 'process_type')
                            ->groupBy('process_type')
                            ->get()
                            ->toArray();
        //格式化数据
        foreach($result as $k => $v){
            $data[$v['process_type']] = $v['num'];
        }

        return view('auditMy.index', $data);
    }

    //获取审核信息
    public function getAuditList(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        $input = Input::all();
        $rules = [
            'type' => 'required',
        ];
        $message = [
            'type.required' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('false', $validator->errors()->first());
        }

        //获取记录总数
        $total = AuditInfoDb::leftjoin('users', 'users.user_id', '=', 'audit_info.created_user')
            ->select('users.user_name', 'audit_info.process_id', 'audit_info.process_title',
                'audit_info.status', 'audit_info.created_at')
            ->where('audit_info.process_type', $input['type'])
            ->where('audit_info.process_audit_user', session('userInfo.user_id'))
            ->count();
        //获取数据
        $result = AuditInfoDb::leftjoin('users', 'users.user_id', '=', 'audit_info.created_user')
            ->select('users.user_name', 'audit_info.process_id', 'audit_info.process_title',
                'audit_info.status', 'audit_info.created_at')
            ->where('audit_info.process_type', $input['type'])
            ->where('audit_info.process_audit_user', session('userInfo.user_id'))
            ->orderBy('status', 'ASC')
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

    //获取审核信息详情
    public function getAuditInfo()
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('auditMy.index'));
        }
        $id = $input['id'];
        
        //获取审核内容信息
        $data['audit'] = AuditInfoDb::leftjoin('users', 'users.user_id', '=', 'audit_info.created_user')
                            ->select('users.user_name', 'audit_info.*')
                            ->where('audit_info.process_id', $id)
                            ->where(function ($query) {
                                $query->where('audit_info.process_audit_user', session('userInfo.user_id'))
                                        ->orWhereIn('audit_info.process_user_res', array('|'.session('userInfo.user_id').'|'));
                            })
                            ->get()
                            ->first();
        if(!$data['audit']){
            return redirectPageMsg('-1', '流程不存在', route('auditMy.index'));
        };
        $data['audit'] = $data['audit']->toArray();
        //获取审批结果
        $data['auditRes'] = AuditInfoTextDb::leftjoin('users', 'users.user_id', '=', 'audit_info_text.created_user')
                                    ->select('users.user_name', 'audit_info_text.*')
                                    ->where('audit_info_text.process_id', $id)
                                    ->orderBy('audit_sort', 'DESC')
                                    ->get()
                                    ->toArray();

        switch ($data['audit']['process_type'])
        {
            case "budget":
                $type = "Budget";//模版名称
                $data['data'] = $this->getBudget($data['audit']['process_app']);
            break;
            case "budgetSum":
                $type = "BudgetSum";//模版名称
                $data['data'] = $this->getBudgetSum($data['audit']['process_app']);
                break;
            case "reimburse":
                $type = "Reimburse";//模版名称
                $data['data'] = $this->getReimburse($data['audit']['process_app']);
                break;
            case "contract":
                $type = "Contract";//模版名称
                $data['data'] = $this->getContract($data['audit']['process_app']);
                break;
        }

        if(!$data['data']){
            return redirectPageMsg('-1', '审核内容不存在', route('auditMy.index'));
        };
     
        $data['process_id'] = $data['audit']['process_id'];
        //p($data);
        return view("auditMy.list$type", $data);
    }
    
    //添加审批结果
    public function createAuditRes()
    {
        //验证表单
        $input = Input::all();

        $rules = [
            'process_id' => 'required|between:32,32',
            'audit_res' => 'required|digits_between:0,11|numeric',
        ];
        $message = [
            'process_id.required' => '参数不存在',
            'process_id.between' => '参数错误',
            'audit_res.required' => '请选择审批结果',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('auditMy.getAuditInfo')."?id=".$input['process_id']);
        }

        $audit = AuditInfoDb::where('process_id', $input['process_id'])
                            ->get()
                            ->first();
        if(!$audit){
            return redirectPageMsg('-1', '流程不存在', route('auditMy.index'));
        };
        if($audit['process_audit_user'] != session('userInfo.user_id')){
            return redirectPageMsg('-1', '审批失败，审批人错误', route('auditMy.index'));
        };

        //事务处理
        $result = DB::transaction(function () use($input, $audit) {
            //获取审批记录数
            $sort = AuditInfoTextDb::where('process_id', $input['process_id'])
                            ->count();
            $sort++;
            $text['audit_text_id'] = getId();
            $text['process_id'] = $input['process_id'];
            $text['created_user'] = session('userInfo.user_id');
            $text['audit_text'] = $input['audit_text'];
            $text['audit_sort'] = $sort;
            $text['audit_res'] = $input['audit_res'];
            $text['created_at'] = date("Y-m-d H:i:s", time());
            $text['updated_at'] = date("Y-m-d H:i:s", time());
            //更新下一位审批人
            $oldUser = $audit['process_user_res'] ? explode(',', $audit['process_user_res']) : '';
            $nextUser = explode(',', $audit['process_users']);

            //获取剩余审批人数
            if($oldUser){
                foreach($oldUser as $v){
                    $v = substr($v, 1, (strlen($v)-2));
                    $k = array_search($v, $nextUser);
                    array_pull($nextUser, $k);
                }
            }
            $nextUser = array_values($nextUser);
            //审批人员对应位置
            $userKey = array_search($audit['process_audit_user'], $nextUser);
            $auditUserNum = count($nextUser)-1;
      
            //历史审批人
            if($audit['process_user_res']){
                $process_user_res = explode(',', $audit['process_user_res']);
            }
            $process_user_res[] = '|'.$audit['process_audit_user'].'|';
            //是否审批结束
            if($userKey == $auditUserNum || $input['audit_res'] == '1003'){
                $info['status'] = '1001';
                $info['process_audit_user'] = 0;
                $info['process_user_res'] = implode(',', $process_user_res);
                //更新应用
                switch ($audit['process_type'])
                {
                    case "budget":
                        $this->updateBudget($audit['process_app'], $input['audit_res']);
                        break;
                    case "budgetSum":
                        $this->updateBudgetSum($audit['process_app'], $input['audit_res']);
                        break;
                    case "reimburse":
                        $this->updateReimburse($audit['process_app'], $input['audit_res']);
                        break;
                    case "contract":
                        $this->updateContract($audit['process_app'], $input['audit_res']);
                        break;
                }
            }else{
                $info['process_user_res'] = implode(',', $process_user_res);
                $info['process_audit_user'] = $nextUser[$userKey+1];
            }

            //更新审批列表
            AuditInfoDb::where('process_id', $input['process_id'])
                    ->update($info);
            //更新审批结果
            AuditInfoTextDb::insert($text);

            return true;
        });

        if($result){
            return redirectPageMsg('1', '审批成功', route('auditMy.index'));
        }else{
            return redirectPageMsg('-1', '审批失败', route('auditMy.getAuditInfo')."?id=".$input['process_id']);
        }
    }

    //获取审批流程名单
    public function getAuditUsers(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }
        $input = Input::all();

        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }
        $id = $input['id'];
        //获取审核内容信息
        $audit = AuditInfoDb::where('process_id', $id)
                            ->select('process_users', 'process_audit_user')
                            ->get()
                            ->first();
        if(!$audit){
            return redirectPageMsg('-1', '流程信息获取错误，请刷新页面重试', route('auditMy.index'));
        };
        $audit = $audit->toArray();
        //格式化流程
        $data['audit_user'] = $audit['process_audit_user'];
        $audit = explode(',', $audit['process_users']);

        $result = UserDb::leftjoin('users_base AS ub', 'users.user_id', '=', 'ub.user_id')
            ->leftjoin('department AS dep', 'ub.department', '=', 'dep.dep_id')
            ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->select('dep.dep_name', 'pos.pos_name', 'users.user_name', 'users.user_id AS uid')
            ->whereIn('users.user_id', $audit)
            ->get()
            ->toArray();

        //格式化数据
        $data['auditProcess'] = array();
        $data['status'] = 1;
        foreach($audit as $k => $v){
            foreach($result as $u => $d){
                if($v == $d['uid']) $data['auditProcess'][] = $d;
            }
        }
            
        //返回结果
        ajaxJsonRes($data);
    }

    /*-----------------------预算类-----------------------*/
    //预算信息
    private function getBudget($id)
    {
        $result = BudgetDb::leftjoin('department AS dep', 'dep.dep_id', '=', 'budget.department')
            ->where('budget.budget_id', $id)
            ->where('budget.budget_sum', '0')
            ->select('budget.*', 'dep.dep_name')
            ->get()
            ->first();
        if(!$result){
            return false;
        }
        $result = $result->toArray();
        return $result;
    }
    //更新预算信息
    private function updateBudget($id, $status){
        $data['status'] = $status == '1002' ? '1' : $status;
        //更新预算
        BudgetDb::where('budget_id', $id)
            ->where('budget_sum', '0')
            ->where('status', '1009')
            ->update($data);
        BudgetSDb::where('budget_id', $id)
            ->where('status', '1009')
            ->update($data);
        BudgetSDDb::where('budget_id', $id)
            ->where('status', '1009')
            ->update($data);
    }
    //汇总预算信息
    private function getBudgetSum($id)
    {
        $result = BudgetDb::where('budget_id', $id)
            ->where('budget_sum', '1')
            ->get()
            ->first();
        if(!$result){
            return false;
        }
        //获取子预算
        $budget_ids = explode(',', $result['budget_ids']);
        $result['budget'] = BudgetDb::whereIn('budget_id', $budget_ids)
            ->select('budget_name AS name', 'budget_num AS budget_num')
            ->get()
            ->toArray();
        if(!$result['budget']){
            return false;
        }

        $result = $result->toArray();
        return $result;
    }
    //更新汇总预算信息
    private function updateBudgetSum($id, $status){
        $data['status'] = $status == '1002' ? '1' : $status;
        //更新预算
        BudgetDb::where('budget_id', $id)
            ->where('budget_sum', '1')
            ->where('status', '1009')
            ->update($data);
        BudgetSDb::where('budget_id', $id)
            ->where('status', '1009')
            ->update($data);
        BudgetSDDb::where('budget_id', $id)
            ->where('status', '1009')
            ->update($data);
    }

    /*-----------------------费用报销-----------------------*/
    //报销单信息
    private function getReimburse($id)
    {
        //获取编辑状态单据
        $result = ExpenseDb::from('expense AS exp')
            ->leftJoin('department AS dep', 'dep.dep_id','=','exp.expense_dep')
            ->leftJoin('users AS u', 'u.user_id','=','exp.expense_user')
            ->select('u.user_name AS user_name', 'dep.dep_name AS dep_name', 'exp.expense_num', 'exp.expense_id',
                'exp.expense_title', 'exp.expense_date', 'exp.expense_status')
            ->where('exp.expense_type', 'reimburse')
            ->get()
            ->first();
        if(!$result){
            return false;
        }
        //获取明细
        $result['expMain'] = ExpenseMainDb::from('expense_main AS expM')
            ->leftjoin('expense_enclosure AS expE', 'expM.exp_id', '=', 'expE.exp_id')
            ->leftjoin('subjects AS sub', 'expM.subject_id_debit', '=', 'sub.sub_id')
            ->where('expM.expense_id', $id)
            ->select('expM.exp_id', 'expM.exp_remark', 'expM.exp_amount', 'expM.enclosure', 'expE.enclo_url AS url',
                'sub.sub_name AS exp_debit', 'sub.sub_pid AS exp_debit_pid')
            ->orderBy('expM.created_at', 'asc')
            ->get()
            ->toArray();

        return $result;
    }
    //更新报销单信息
    private function updateReimburse($id, $status){
        //转换状态
        $data['expense_status'] = $status == '1002' ? '203' : $status;
        $data['expense_cashier'] = $status == '1002' ? session('userInfo.sysConfig.reimburse.userCashier') : '';
        //更新单据状态
        ExpenseDb::where('expense_id', $id)
            ->where('expense_type', 'reimburse')
            ->where('expense_status', '1009')
            ->update($data);
        //获取单据信息
        $expense = ExpenseDb::where('expense_id', $id)
            ->where('expense_type', 'reimburse')
            ->select('expense_num', 'expense_user', 'expense_status')
            ->get()
            ->first();

        if($expense['expense_status'] == '203'){
            //发送出纳通知
            $notice[0]['notice_id'] = getId();
            $notice[0]['notice_app'] = $id;//需要确认操作
            $notice[0]['notice_message'] = '报销单据：编号'.$expense['expense_num'].'。已通过审批，等待付款。';
            $notice[0]['notice_user'] = session('userInfo.sysConfig.reimburse.userCashier');
            //发送用户通知
            $notice[1]['notice_id'] = getId();
            $notice[1]['notice_app'] = $id;//需要确认操作
            $notice[1]['notice_message'] = '报销单据：编号'.$expense['expense_num'].'。已通过审批，等待出纳付款。';
            $notice[1]['notice_user'] = $expense['expense_user'];
            $this->createNotice($notice, 1);
        }else{
            //发送用户通知
            $notice['notice_id'] = getId();
            $notice['notice_app'] = $id;//需要确认操作
            $notice['notice_message'] = '报销单据：编号'.$expense['expense_num'].'。已通过审批，不批准。';
            $notice['notice_user'] = $expense['expense_user'];
            $this->createNotice($notice);
        }

    }

    /*-----------------------费用报销-----------------------*/
    //合同
    private function getContract($id){
        //合同信息
        $data['contract'] = ContractDb::from('contract AS cont')
            ->leftJoin('sys_assembly AS sysAssType', 'cont.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'cont.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('budget AS budget', 'cont.cont_budget','=','budget.budget_name')
            ->leftJoin('subjects AS sub', 'cont.cont_subject','=','sub.sub_id')
            ->select('cont.cont_id AS id',
                'cont.cont_type AS contract_type',
                'cont.cont_num AS contract_num',
                'cont.cont_name AS contract_name',
                'cont.cont_start AS date_start',
                'cont.cont_end AS date_end',
                'cont.cont_sum_amount AS contract_amount',
                'cont.cont_status AS status',
                'cont.cont_class AS cont_class',
                'sysAssType.ass_text AS contract_type',
                'sysAssClass.ass_text AS contract_class',
                'cont.cont_status AS status',
                'budget.budget_name',
                'sub.sub_name',
                'cont.cont_remark',
                'cont.cont_parties')
            ->where('cont.cont_id', $id)
            ->first();
        if(!$data['contract']){
            return false;
        }
        //合同期间
        $data['contDetails'] = ContDetailsDb::where('cont_id', $data['contract']->id)
            ->orderBy('cont_details_date', 'ASC')
            ->get()
            ->toArray();
        if(!$data['contDetails']){
            return false;
        }
        //合同附件
        $data['contEnclo'] = ContEncloDb::where('cont_id', $data['contract']->id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->toArray();
        //客户信息
        $parties = '';
        if($data['contract']->cont_class == session('userInfo.sysConfig.contract.income')){
            $parties = CustomerDb::where('cust_id', $data['contract']->cont_parties)
                ->first();
        }
        if($data['contract']->cont_class == session('userInfo.sysConfig.contract.payment')){
            $parties = SupplierDb::where('supp_id', $data['contract']->cont_parties)
                ->first();
        }
        if(!$parties){
            return false;
        }
        $data['contract']['parties'] = $parties['cust_name'];

        return $data;
    }
    //更新合同
    private function updateContract($id, $status){
        //转换状态
        $data['cont_status'] = $status == '1002' ? '301' : $status;
        //更新单据状态
        ContractDb::where('cont_id', $id)
            ->where('cont_status', '1009')
            ->update($data);
        //更新单据状态
        ContDetailsDb::where('cont_id', $id)
            ->where('cont_status', '1009')
            ->update($data);
        //获取单据信息
        $contract = ContractDb::where('cont_id', $id)
            ->select('cont_num', 'created_user', 'cont_status')
            ->get()
            ->first();

        //发送通知
        $notice['notice_id'] = getId();
        $notice['notice_app'] = $id;//需要确认操作
        $notice['notice_message'] = '合同编号：'.$contract['cont_num'].'。已通过审批。';
        $notice['notice_user'] = $contract['cont_status'];
        $this->createNotice($notice);
    }
}
