<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\AuditProcess\AuditProcessModel AS auditProcessDb;
use App\Http\Models\User\UserModel AS UserDb;

class AuditProcessController extends Common\CommonController
{
    public function index()
    {
        return view('auditProcess.index');
    }

    public function getAudit()
    {
        //获取参数
        $input = Input::all();

        //分页
        $take = !empty($input['length']) ? intval($input['length']) : 10;//数据长度
        $skip = !empty($input['start']) ? intval($input['start']) : 0;//从多少开始

        //获取记录总数
        $total = auditProcessDb::count();
        //获取数据
        $result = auditProcessDb::leftjoin('department AS dep', 'dep.dep_id', '=', 'audit_process.audit_dep')
            ->select('audit_process.*', 'dep.dep_name AS department')
            ->skip($skip)
            ->take($take)
            ->orderBy('audit_process.audit_dep', 'asc')
            ->get()
            ->toArray();

        //创建结果数据
        $data['draw'] = isset($input['draw']) ? intval($input['draw']) : 1;
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = '1';

        //返回结果
        ajaxJsonRes($data);
    }
    
    //添加审核流程视图
    public function addAudit()
    {
        return view('auditProcess.addAudit');
    }

    //添加岗位
    public function createAudit()
    {
        //验证表单
        $input = Input::all();

        $rules = [
            'audit_name' => 'required|between:1,100',
            'audit_user' => 'required',
        ];
        $message = [
            'audit_name.required' => '请填写审核流程名称',
            'audit_name.between' => '审核流程名称字符数过多',
            'audit_user.required' => '请添加审核流程人员',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('auditProcess.addAudit'));
        }

        //判断审核流程是否存在
        $result = auditProcessDb::where('audit_type',$input['audit_type'])
                                ->where('audit_dep', $input['dep_id'])
                                ->first();
        if($result){
            redirectPageMsg('-1', '审核流程已存在', route('auditProcess.addAudit'));
        }

        //格式化数据
        $input['audit_status'] = array_key_exists('audit_status', $input) ? 1 : 0;

        //创建审核流程
        $auditProcessDb = new auditProcessDb();
        $auditProcessDb->audit_dep = $input['dep_id'];
        $auditProcessDb->audit_type = $input['audit_type'];
        $auditProcessDb->audit_name = $input['audit_name'];
        $auditProcessDb->audit_process = $input['audit_user'];
        $auditProcessDb->status = $input['audit_status'];
        $result = $auditProcessDb->save();

        if($result){
            redirectPageMsg('1', "添加成功", route('auditProcess.index'));
        }else{
            redirectPageMsg('-1', "添加失败", route('auditProcess.addAudit'));
        }
    }

    //编辑审核流程视图
    public function editAudit($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt")){
            redirectPageMsg('-1', '参数错误', route('auditProcess.index'));
        };

        //获取数据
        $data['audit'] = auditProcessDb::leftjoin('department AS dep', 'dep.dep_id', '=', 'audit_process.audit_dep')
            ->select('audit_process.*', 'dep.dep_name AS department')
            ->where('audit_id', $id)
            ->first()
            ->toArray();

        //格式化流程
        $audit = explode(',', $data['audit']['audit_process']);

        $user = UserDb::leftjoin('users_base AS ub', 'users.user_id', '=', 'ub.user_id')
            ->leftjoin('department AS dep', 'ub.department', '=', 'dep.dep_id')
            ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->select('dep.dep_name', 'pos.pos_name', 'users.user_name', 'users.user_id AS uid')
            ->whereIn('users.user_id', $audit)
            ->get()
            ->toArray();

        //格式化数据
        $data['audit_user'] = array();
        foreach($audit as $k => $v){
            foreach($user as $u => $d){
                if($v == $d['uid']) $data['audit_user'][] = $d;
            }
        }
        $data['audit_user'] = json_encode($data['audit_user']);
        return view('auditProcess.editAudit', $data);
    }

    //更新审核流程
    public function updateAudit()
    {
        //验证表单
        $input = Input::all();

        //检测id类型是否整数
        if(!array_key_exists('audit_id', $input)){
            redirectPageMsg('-1', '参数错误', route('auditProcess.editAudit')."/".$input['audit_id']);
        };
        $rules = [
            'audit_name' => 'required|between:1,100',
            'audit_user' => 'required',
            'audit_id' => 'required|digits_between:0,11',
        ];
        $message = [
            'audit_name.required' => '请填写审核流程名称',
            'audit_name.between' => '审核流程名称字符数过多',
            'audit_user.required' => '请添加审核流程人员',
            'audit_id.required' => '参数错误',
            'audit_id.digits_between' => '参数格式错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('auditProcess.editAudit')."/".$input['audit_id']);
        }

        //判断审核流程是否存在
        $result = auditProcessDb::where('audit_id',$input['audit_id'])
                                ->first();
        if(!$result){
            redirectPageMsg('-1', '审核流程不存在！', route('auditProcess.index'));
        }

        //判断审核流程是否重复
        $result = auditProcessDb::where('audit_id','<>', $input['audit_id'])
                                ->where('audit_type',$input['audit_type'])
                                ->where('audit_dep', $input['dep_id'])
                                ->first();
        if($result){
            redirectPageMsg('-1', '审核流程已存在', route('auditProcess.editAudit')."/".$input['audit_id']);
        }

        //格式化数据
        $input['audit_status'] = array_key_exists('audit_status', $input) ? 1 : 0;

        //更新审核流程
        $data = array();
        $data['audit_dep'] = $input['dep_id'];
        $data['audit_type'] = $input['audit_type'];
        $data['audit_name'] = $input['audit_name'];
        $data['audit_process'] = $input['audit_user'];
        $data['status'] = $input['audit_status'];

        $result = auditProcessDb::where('audit_id', $input['audit_id'])
            ->update($data);

        if($result){
            redirectPageMsg('1', "编辑成功", route('auditProcess.index'));
        }else{
            redirectPageMsg('-1', "编辑失败", route('auditProcess.editAudit')."/".$input['audit_id']);
        }
    }
    
    //获取审核流程详情
    public function auditInfo(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //获取数据
        $result = auditProcessDb::leftjoin('department AS dep', 'dep.dep_id', '=', 'audit_process.audit_dep')
            ->select('audit_process.*', 'dep.dep_name AS department')
            ->where('audit_id', $input['id'])
            ->first()
            ->toArray();

        //格式化流程
        $audit = explode(',', $result['audit_process']);

        $result = UserDb::leftjoin('users_base AS ub', 'users.user_id', '=', 'ub.user_id')
                        ->leftjoin('department AS dep', 'ub.department', '=', 'dep.dep_id')
                        ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
                        ->select('dep.dep_name', 'pos.pos_name', 'users.user_name', 'users.user_id AS uid')
                        ->whereIn('users.user_id', $audit)
                        ->get()
                        ->toArray();

        //格式化数据
        $data = array();
        foreach($audit as $k => $v){
            foreach($result as $u => $d){
                if($v == $d['uid']) $data[] = $d;
            }
        }

        //返回结果
        ajaxJsonRes($data);
    }
}
