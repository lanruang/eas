<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\AuditProcess\AuditInfoModel AS AuditInfoDb;
use App\Http\Models\Budget\BudgetModel AS BudgetDb;
use App\Http\Models\AuditProcess\AuditInfoTextModel AS AuditInfoTextDb;
use Illuminate\Support\Facades\DB;

class AuditMyController extends Common\CommonController
{
    public function index()
    {
        $data['budget'] = '';
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
            return echoAjaxJson('false', $validator->errors()->first());
        }

        //获取记录总数
        $total = AuditInfoDb::count();
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
            return redirectPageMsg('-1', '缺少必要参数', route('auditMy.index'));
        };

        //获取审核内容信息
        $audit = AuditInfoDb::where('process_id', $id)
                            ->get()
                            ->first();
        if(!$audit){
            return redirectPageMsg('-1', '流程不存在', route('auditMy.index'));
        };
        //获取审批结果
        $data['auditRes'] = AuditInfoTextDb::leftjoin('users', 'users.user_id', '=', 'audit_info_text.created_user')
                                    ->select('users.user_name', 'audit_info_text.*')
                                    ->where('audit_info_text.process_id', $id)
                                    ->orderBy('audit_sort', 'DESC')
                                    ->get()
                                    ->toArray();

        switch ($audit->process_app)
        {
            case "budget":
                $type = "Budget";
                $data['budget'] = $this->getBudget($audit->process_app);
            break;
            default:
                $type = "Budget";
                $data['budget'] = $this->getBudget($audit->process_app);
        }
        if(!$data['budget']){
            return redirectPageMsg('-1', '审核内容不存在', route('auditMy.index'));
        };
        $data['process_id'] = $audit->process_id;

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
            return redirectPageMsg('-1', $validator->errors()->first(), route('auditMy.getAuditInfo')."/".$input['process_id']);
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

        //格式化状态
        $input['audit_res'] = array_key_exists('audit_res', $input) ? 1 : 0;

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
            $nextUser = explode(',', $audit['process_users']);
            $userKey = array_search($audit['process_audit_user'], $nextUser);
            $auditUserNum = count($nextUser)-1;
            //历史审批人
            if($audit['process_user_res']){
                $process_user_res = explode(',', $audit['process_user_res']);
            }
            $process_user_res[] = '|'.$audit['process_audit_user'].'|';
            if($userKey == $auditUserNum){
                $info['status'] = '1001';
                $info['process_audit_user'] = 0;
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
            return redirectPageMsg('-1', '审批失败', route('auditMy.getAuditInfo')."/".$input['process_id']);
        }
    }
    
    //预算信息
    private function getBudget($id)
    {
        $result = BudgetDb::where('budget_id', $id)
            ->get()
            ->first();
        if(!$result){
            return false;
        }

        return $result;
    }
}
