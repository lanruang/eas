<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\AuditProcess\AuditInfoModel AS AuditInfoDb;
use App\Http\Models\Budget\BudgetModel AS BudgetDb;
use App\Http\Models\Subjects\SubjectsModel AS SubjectsDb;
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

        switch ($audit->process_type)
        {
            case "budget":
                $type = "Budget";
                $data = $this->getBudget($audit->process_app);
            break;
            default:
                $type = "Budget";
                $data = $this->getBudget($audit->process_app);
        }
        if(!$data){
            return redirectPageMsg('-1', '审核内容不存在', route('auditMy.index'));
        };

        return view("auditMy.list$type", $data);
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
