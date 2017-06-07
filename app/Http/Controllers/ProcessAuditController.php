<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\ProcessAuditModel AS processAuditDb;
use App\Http\Models\DepartmentModel AS DepartmentDb;

class ProcessAuditController extends Common\CommonController
{
    public function index()
    {
        return view('processAudit.index');
    }

    public function getProcessAudit()
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
    
    //添加审核流程视图
    public function addProcessAudit()
    {
        //部门下拉菜单
        $result = DepartmentDb::select('dep_id AS id', 'dep_name AS text', 'dep_pid AS pid')
            ->where('status', 1)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = DepartmentDb::where('status', 1)
            ->min('dep_pid');
        $result = !getTreeT($result, $selectPid) ? $result = array() : getTreeT($result, $selectPid);

        $data['select'] = json_encode($result);

        return view('processAudit.addProcessAudit', $data);
    }
}
