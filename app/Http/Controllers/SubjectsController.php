<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\Subjects\SubjectsModel AS SubjectsDb;


class SubjectsController extends Common\CommonController
{
    public function index()
    {
        //树形科目
        $result = SubjectsDb::select('sub_id AS id', 'sub_name AS text', 'sub_pid AS pid', 'sub_ip')
            ->orderBy('sub_ip', 'asc')
            ->get()
            ->toArray();
        //获取最小pid
        $selectPid = SubjectsDb::where('status', 1)
            ->min('sub_pid');
        $result = !getTreeT($result, $selectPid) ? $result = array() : getTreeT($result, $selectPid);

        $subject['select'] = json_encode($result);
        return view('subjects.index', $subject);
    }

    public function getSubjects()
    {
        //获取参数
        $input = Input::all();
        $pid = isset($input['pid']) ? $input['pid'] : '0';
        //获取当前科目名称
        if($pid != '0'){
            $subject = SubjectsDb::select('sub_id AS id', 'sub_name AS name', 'sub_pid AS pid')
                ->where('sub_id', $pid)
                ->first()
                ->toArray();
            $data['subject'] = $subject;
        }

        //分页
        $take = !empty($input['length']) ? intval($input['length']) : 10;//数据长度
        $skip = !empty($input['start']) ? intval($input['start']) : 0;//从多少开始

        //获取记录总数
        $total = SubjectsDb::where('sub_pid', $pid)
            ->count();
        //获取数据
        $result = SubjectsDb::select('sub_id AS id', 'sub_ip AS sub_ip', 'sub_type AS type', 'sub_name AS name', 'status', 'sub_pid AS pid')
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
        $result = SubjectsDb::select('sub_id AS id', 'sub_name AS text', 'sub_pid AS pid', 'sub_ip')
            ->orderBy('sub_ip', 'asc')
            ->get()
            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = SubjectsDb::where('status', 1)
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
            'subject_pid' => 'between:0,32'
        ];
        $message = [
            'subject_name.required' => '科目名称未填写',
            'subject_name.max' => '科目名称字符数过多',
            'subject_ip.required' => '科目地址未填写',
            'subject_ip.max' => '科目地址字符数过多',
            'subject_pid.between' => '上级科目参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('subject.addSubjects'));
        }

        //科目是否存在
        $result = SubjectsDb::Where('sub_ip', $input['subject_ip'])
            ->first();
        if($result){
            return redirectPageMsg('-1', "添加失败，科目地址重复", route('subjects.addSubjects'));
        }

        //格式化状态
        $input['subject_status'] = array_key_exists('subject_status', $input) ? 1 : 0;
        $input['subject_budget'] = array_key_exists('subject_budget', $input) ? 1 : 0;

        //添加数据
        $subjectDb = new subjectDb;
        $subjectDb->sub_id = getId();
        $subjectDb->sub_type = $input['subject_type'];
        $subjectDb->sub_ip = $input['subject_ip'];
        $subjectDb->sub_name = $input['subject_name'];
        $subjectDb->sub_pid = $input['subject_pid'];
        $subjectDb->status = $input['subject_status'];
        $subjectDb->sort = 0;
        $result = $subjectDb->save();

        if($result){
            return redirectPageMsg('1', "添加成功", route('subjects.addSubjects'));
        }else{
            return redirectPageMsg('-1', "添加失败", route('subjects.addSubjects'));
        }
    }

    //编辑权限视图
    public function editSubjects()
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('subjects.index'));
        }
        $id = $input['id'];
        //获取科目信息
        $subject = SubjectsDb::leftjoin('subjects AS sub', 'sub.sub_id','=','subjects.sub_pid')
            ->select('sub.sub_name AS subject_Fname', 'sub.sub_ip AS subject_Fip',
                'subjects.sub_name', 'subjects.sub_type', 'subjects.status', 'subjects.sub_ip',
                'subjects.sub_pid', 'subjects.sub_id')
            ->where('subjects.sub_id', $id)
            ->first()
            ->toArray();
        if(!$subject){
            return redirectPageMsg('-1', "科目获取失败", route('subjects.index'));
        }

        //下拉菜单信息
        $result = SubjectsDb::select('sub_id AS id', 'sub_name AS text', 'sub_pid AS pid', 'sub_ip')
            ->orderBy('sub_ip', 'asc')
            ->get()
            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = SubjectsDb::where('status', 1)
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
        $rules = [
            'subject_name' => 'required|max:100',
            'subject_ip' => 'required|max:120',
            'subject_pid' => 'between:0,32',
            'subject_id' => 'required|between:32,32'
        ];
        $message = [
            'subject_name.required' => '科目名称未填写',
            'subject_name.max' => '科目名称字符数过多',
            'subject_ip.required' => '科目地址未填写',
            'subject_ip.max' => '科目地址字符数过多',
            'subject_pid.between' => '上级科目参数错误',
            'subject_id.required' => '参数不存在',
            'subject_id.integer' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('subjects.editSubjects')."?id=".$input['subjects_id']);
        }

        //科目是否存在
        $result = SubjectsDb::Where('sub_id', '<>', $input['subject_id'])
            ->Where('sub_ip', $input['subject_ip'])
            ->first();

        if($result){
            return redirectPageMsg('-1', "添加失败，科目名称或地址重复", route('subjects.editSubjects')."?id=".$input['subject_id']);
        }

        //格式化状态
        $input['subject_status'] = array_key_exists('subject_status', $input) ? 1 : 0;

        //格式化数据
        $data['sub_pid'] = $input['subject_pid'];
        $data['sub_name'] = $input['subject_name'];
        $data['sub_ip'] = $input['subject_ip'];
        $data['sub_type'] = $input['subject_type'];
        $data['status'] = $input['subject_status'];

        //更新权限
        $result = SubjectsDb::where('sub_id', $input['subject_id'])
            ->update($data);
        if($result){
            return redirectPageMsg('1', "编辑成功", route('subjects.index'));
        }else{
            return redirectPageMsg('-1', "编辑失败", route('subjects.editSubjects')."?id=".$input['subject_id']);
        }
    }

}
