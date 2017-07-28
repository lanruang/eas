<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\Subjects\SubjectsModel AS subjectDb;


class SubjectsController extends Common\CommonController
{
    public function index()
    {
        //树形科目
        $result = subjectDb::select('sub_id AS id', 'sub_name AS text', 'sub_pid AS pid', 'sub_ip')
            ->orderBy('sub_ip', 'asc')
            ->get()
            ->toArray();
        //获取最小pid
        $selectPid = subjectDb::where('status', 1)
            ->min('sub_pid');
        $result = !getTreeT($result, $selectPid) ? $result = array() : getTreeT($result, $selectPid);

        $subject['select'] = json_encode($result);
        return view('subjects.index', $subject);
    }

    public function getSubjects()
    {
        //获取参数
        $input = Input::all();
        $pid = isset($input['pid']) ? intval($input['pid']) : 0;
        //获取当前科目名称
        if($pid > 0){
            $subject = subjectDb::select('sub_id AS id', 'sub_name AS name', 'sub_pid AS pid')
                ->where('sub_id', $pid)
                ->first()
                ->toArray();
            $data['subject'] = $subject;
        }

        //分页
        $take = !empty($input['length']) ? intval($input['length']) : 10;//数据长度
        $skip = !empty($input['start']) ? intval($input['start']) : 0;//从多少开始

        //获取记录总数
        $total = subjectDb::where('sub_pid', $pid)
            ->count();
        //获取数据
        $result = subjectDb::select('sub_id AS id', 'sub_ip AS sub_ip', 'sub_type AS type', 'sub_name AS name', 'status', 'sub_pid AS pid')
            ->where('sub_pid', $pid)
            ->skip($skip)
            ->take($take)
            ->orderBy('sub_ip', 'asc')
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

    //添加权限视图
    public function addSubjects(){
        //下拉菜单信息
        $result = subjectDb::select('sub_id AS id', 'sub_name AS text', 'sub_pid AS pid', 'sub_ip')
            ->orderBy('sub_ip', 'asc')
            ->get()
            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = subjectDb::where('status', 1)
            ->min('sub_pid');
        $result = !getTreeT($result, $selectPid) ? $result = array() : getTreeT($result, $selectPid);

        $subject['select'] = json_encode($result);
        return view('subjects.addSubjects', $subject);
    }

    //添加权限
    public function createSubjects()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'subject_name' => 'required|max:100',
            'subject_ip' => 'required|max:120',
            'subject_pid' => 'digits_between:0,11'
        ];
        $message = [
            'subject_name.required' => '科目名称未填写',
            'subject_name.max' => '科目名称字符数过多',
            'subject_ip.required' => '科目地址未填写',
            'subject_ip.max' => '科目地址字符数过多',
            'subject_pid.digits_between' => '上级科目参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('subject.addSubjects'));
        }

        //科目是否存在
        $result = subjectDb::Where('sub_ip', $input['subject_ip'])
            ->first();
        if($result){
            redirectPageMsg('-1', "添加失败，科目地址重复", route('subjects.addSubjects'));
        }

        //格式化状态
        $input['subject_status'] = array_key_exists('subject_status', $input) ? 1 : 0;
        $input['subject_budget'] = array_key_exists('subject_budget', $input) ? 1 : 0;

        //添加数据
        $subjectDb = new subjectDb;
        $subjectDb->sub_type = $input['subject_type'];
        $subjectDb->sub_ip = $input['subject_ip'];
        $subjectDb->sub_name = $input['subject_name'];
        $subjectDb->sub_pid = $input['subject_pid'];
        $subjectDb->status = $input['subject_status'];
        $subjectDb->sub_budget = $input['subject_budget'];
        $subjectDb->sort = 0;
        $result = $subjectDb->save();

        if($result){
            redirectPageMsg('1', "添加成功", route('subjects.addSubjects'));
        }else{
            redirectPageMsg('-1', "添加失败", route('subjects.addSubjects'));
        }
    }

    //编辑权限视图
    public function editSubjects($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            redirectPageMsg('-1', '缺少必要参数', route('subjects.index'));
        };

        //获取科目信息
        $subject = subjectDb::leftjoin('subjects AS sub', 'sub.sub_id','=','subjects.sub_pid')
            ->select('sub.sub_name AS subject_Fname', 'sub.sub_ip AS subject_Fip',
                'subjects.sub_name', 'subjects.sub_type', 'subjects.status', 'subjects.sub_ip',
                'subjects.sub_pid', 'subjects.sub_id', 'subjects.sub_budget')
            ->where('subjects.sub_id', $id)
            ->first()
            ->toArray();
        if(!$subject){
            redirectPageMsg('-1', "科目获取失败", route('subjects.index'));
        }

        //下拉菜单信息
        $result = subjectDb::select('sub_id AS id', 'sub_name AS text', 'sub_pid AS pid', 'sub_ip')
            ->orderBy('sub_ip', 'asc')
            ->get()
            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = subjectDb::where('status', 1)
            ->min('sub_pid');
        $result = !getTreeT($result, $selectPid) ? $result = array() : getTreeT($result, $selectPid);

        $subject['select'] = json_encode($result);

        return view('subjects.editSubjects', $subject);
    }

    //编辑权限
    public function updateSubjects()
    {
        //验证表单
        $input = Input::all();

        //检测id类型是否整数
        if(!array_key_exists('subject_id', $input)){
            redirectPageMsg('-1', '缺少必要参数', route('subjects.index'));
        };
        $rules = [
            'subject_name' => 'required|max:100',
            'subject_ip' => 'required|max:120',
            'subject_pid' => 'digits_between:0,11'
        ];
        $message = [
            'subject_name.required' => '科目名称未填写',
            'subject_name.max' => '科目名称字符数过多',
            'subject_ip.required' => '科目地址未填写',
            'subject_ip.max' => '科目地址字符数过多',
            'subject_pid.digits_between' => '上级科目参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('subjects.editSubjects')."/".$input['subjects_id']);
        }

        //科目是否存在
        $result = subjectDb::Where('sub_id', '<>', $input['subject_id'])
            ->Where('sub_ip', $input['subject_ip'])
            ->first();

        if($result){
            redirectPageMsg('-1', "添加失败，科目名称或地址重复", route('subjects.editSubjects')."/".$input['subject_id']);
        }

        //格式化状态
        $input['subject_status'] = array_key_exists('subject_status', $input) ? 1 : 0;
        $input['subject_budget'] = array_key_exists('subject_budget', $input) ? 1 : 0;

        //格式化数据
        $data['sub_pid'] = $input['subject_pid'];
        $data['sub_name'] = $input['subject_name'];
        $data['sub_ip'] = $input['subject_ip'];
        $data['sub_type'] = $input['subject_type'];
        $data['status'] = $input['subject_status'];
        $data['sub_budget'] = $input['subject_status'];

        //更新权限
        $result = subjectDb::where('sub_id', $input['subject_id'])
            ->update($data);
        if($result){
            redirectPageMsg('1', "编辑成功", route('subjects.index'));
        }else{
            redirectPageMsg('-1', "编辑失败", route('subjects.editSubjects')."/".$input['subject_id']);
        }
    }

}
