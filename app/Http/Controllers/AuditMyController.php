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
use Illuminate\Support\Facades\DB;

class AuditMyController extends Common\CommonController
{
    public function index()
    {
        $data['budget'] = '';
        $data['budgetSum'] = '';
        $data['contract'] = '';
        $data['finance'] = '';
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
    public function getAuditInfo($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            redirectPageMsg('-1', '缺少必要参数', route('auditMy.index'));
        };

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
            redirectPageMsg('-1', '流程不存在', route('auditMy.index'));
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
                $type = "Budget";
                $data['data'] = $this->getBudget($data['audit']['process_app']);
            break;
            case "budgetSum":
                $type = "BudgetSum";
                $data['data'] = $this->getBudgetSum($data['audit']['process_app']);
                break;
            default:
                $type = "Budget";
                $data['data'] = $this->getBudget($data['audit']['process_app']);
        }
        if(!$data['data']){
            redirectPageMsg('-1', '审核内容不存在', route('auditMy.index'));
        };
     
        $data['process_id'] = $data['audit']['process_id'];
   
        return view("auditMy.list$type", $data);
    }
    
    //添加审批结果
    public function createAuditRes()
    {
        //验证表单
        $input = Input::all();

        $rules = [
            'process_id' => 'required|digits_between:0,11|numeric',
            'audit_res' => 'required|digits_between:0,11|numeric',
        ];
        $message = [
            'process_id.required' => '参数不存在',
            'process_id.between' => '参数错误',
            'process_id.numeric' => '参数错误',
            'audit_res.required' => '请选择审批结果',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('auditMy.getAuditInfo')."/".$input['process_id']);
        }

        $audit = AuditInfoDb::where('process_id', $input['process_id'])
                            ->get()
                            ->first();
        if(!$audit){
            redirectPageMsg('-1', '流程不存在', route('auditMy.index'));
        };
        if($audit['process_audit_user'] != session('userInfo.user_id')){
            redirectPageMsg('-1', '审批失败，审批人错误', route('auditMy.index'));
        };

        //事务处理
        $result = DB::transaction(function () use($input, $audit) {
            //获取审批记录数
            $sort = AuditInfoTextDb::where('process_id', $input['process_id'])
                            ->count();
            $sort++;
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
            redirectPageMsg('1', '审批成功', route('auditMy.index'));
        }else{
            redirectPageMsg('-1', '审批失败', route('auditMy.getAuditInfo')."/".$input['process_id']);
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
            'id' => 'required|integer|digits_between:1,11',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.integer' => '参数类型错误',
            'id.digits_between' => '参数错误'
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
            redirectPageMsg('-1', '流程信息获取错误，请刷新页面重试', route('auditMy.index'));
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
        $result = BudgetDb::where('budget_id', $id)
            ->where('budget_sum', '0')
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
}
