<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\DepartmentModel AS DepartmentDb;
use Illuminate\Support\Facades\Input;
use Validator;

class DepartmentController extends Common\CommonController
{
    //部门列表
    public function index()
    {
        return view('department.index');
    }

    //部门列表
    public function getDepartment(Request $request){
        //验证传输方式

        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = DepartmentDb::count();
        //获取数据
        $result = DepartmentDb::leftjoin('users', 'users.user_id', '=', 'dep_leader')
            ->select('dep_id AS id', 'dep_name AS name', 'dep_pid AS pid',  'user_name AS u_name', 'department.is_del AS deleted')
            ->orderBy('sort', 'ASC')
            ->get()
            ->toArray();
        $result = sortTree($result);

        //创建结果数据
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //添加部门视图
    public function addDepartment()
    {
        //获取下拉菜单
        $result = DepartmentDb::select('dep_id AS id', 'dep_name AS text', 'dep_pid AS pid')
            ->where('is_del', 0)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();

        $result = !$result ? $result = array() : getTreeT($result);
        $data['select'] = json_encode($result);
        return view('department.addDepartment', $data);
    }

    //添加部门
    public function createDepartment()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'dep_name' => 'required|between:1,50',
            'dep_leader' => 'between:0,11|numeric',
            'dep_sort' => 'required|between:1,4|numeric',
            'dep_pid' => 'between:0,11|numeric',
        ];
        $message = [
            'dep_name.required' => '部门名称未填写',
            'dep_name.between' => '部门名称字符数过多',
            'dep_leader.between' => '部门负责人参数错误',
            'dep_leader.numeric' => '部门负责人参数错误',
            'dep_sort.required' => '排序未填写',
            'dep_sort.between' => '排序符数过多',
            'dep_sort.numeric' => '参数错误',
            'dep_pid.between' => '上级部门参数错误',
            'dep_pid.numeric' => '上级部门参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('department.addDepartment'));
        }

        //部门是否存在
        $result = DepartmentDb::where('dep_name', $input['dep_name'])->first();
        if($result){
            redirectPageMsg('-1', "添加失败，部门名称重复", route('department.addDepartment'));
        }

        //格式化数据
        $input['dep_leader'] = !$input['dep_leader'] ? 0 : $input['dep_leader'];
        $input['dep_pid'] = !$input['dep_pid'] ? 0 : $input['dep_pid'];

        //创建员工
        $departmentDb = new DepartmentDb();
        $departmentDb->dep_name = $input['dep_name'];
        $departmentDb->dep_leader = $input['dep_leader'];
        $departmentDb->dep_pid = $input['dep_pid'];
        $departmentDb->sort = $input['dep_sort'];
        $departmentDb->is_del = 0;
        $result = $departmentDb->save();

        if($result){
            redirectPageMsg('1', "添加成功", route('department.addDepartment'));
        }else{
            redirectPageMsg('-1', "添加失败", route('department.addDepartment'));
        }
    }
    
    //编辑部门视图
    public function editDepartment($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            redirectPageMsg('-1', '参数错误', route('department.index'));
        };

        //获取部门信息
        $department = DepartmentDb::leftjoin('users', 'users.user_id', '=', 'dep_leader')
            ->select('dep_id AS id', 'dep_name AS name', 'dep_pid AS pid', 'dep_leader AS u_id',  'sort',  'user_name AS u_name')
            ->where('department.dep_id', $id)
            ->get()
            ->first()
            ->toArray();
        if(!$department){
            redirectPageMsg('-1', "参数错误", route('department.index'));
        }
        $department['p_name'] = '';
        //获取上级部门
        if($department['pid'] > 0){
            $departmentP = DepartmentDb::select('dep_name AS name')
                ->where('dep_id', $department['pid'])
                ->get()
                ->first();
            if($departmentP){
                $department['p_name'] = $departmentP->name;
            }
        }

        //获取下拉菜单
        $result = DepartmentDb::select('dep_id AS id', 'dep_name AS text', 'dep_pid AS pid')
            ->where('is_del', 0)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        
        $result = !$result ? $result = array() : getTreeT($result);
        $data['select'] = json_encode($result);
        $data['dep'] = $department;

        return view('department.editDepartment', $data);
    }
    
    //更新部门信息
    public function updateDepartment()
    {
        //验证表单
        $input = Input::all();
        //检测id类型是否整数
        if(!array_key_exists('dep_id', $input)){
            redirectPageMsg('-1', '参数错误', route('department.index'));
        };

        $rules = [
            'dep_name' => 'required|between:1,50',
            'dep_leader' => 'between:0,11|numeric',
            'dep_sort' => 'required|between:1,4|numeric',
            'dep_pid' => 'between:1,11|numeric',
        ];
        $message = [
            'dep_name.required' => '部门名称未填写',
            'dep_name.between' => '部门名称字符数过多',
            'dep_leader.between' => '部门负责人参数错误',
            'dep_leader.numeric' => '部门负责人参数错误',
            'dep_sort.required' => '排序未填写',
            'dep_sort.between' => '排序符数过多',
            'dep_sort.numeric' => '参数错误',
            'dep_pid.between' => '上级部门参数错误',
            'dep_pid.numeric' => '上级部门参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('department.editDepartment')."/".$input['dep_id']);
        }

        //部门是否存在
        $result = DepartmentDb::where('dep_name', $input['dep_name'])->first();
        if($result){
            redirectPageMsg('-1', "修改失败，部门名称重复", route('department.editDepartment')."/".$input['dep_id']);
        }

        //格式化数据
        $data['dep_name'] = $input['dep_name'];
        $data['dep_leader'] = !$input['dep_leader'] ? 0 :$input['dep_leader'];
        $data['dep_pid'] = !$input['dep_pid'] ? 0 :$input['dep_pid'];
        $data['sort'] = $input['dep_sort'];

        //更新数据
        $result = DepartmentDb::where('dep_id', $input['dep_id'])
            ->update($data);

        if($result){
            redirectPageMsg('1', "编辑成功", route('department.index'));
        }else{
            redirectPageMsg('-1', "编辑失败", route('department.editDepartment')."/".$input['dep_id']);
        }
    }

    //删除部门
    public function delDepartment(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }
        $input = Input::all();

        //过滤信息
        $rules = [
            'id' => 'required|integer',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.integer' => '参数类型错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            echoAjaxJson('-1', $validator->errors()->first());
        }

        $data['is_del'] = $input['act'] == '1' ? 1 :0;
        //更改状态
        $result = DepartmentDb::where('dep_id', $input['id'])
            ->update($data);

        if ($result) {
            echoAjaxJson('1', '操作成功');
        } else {
            echoAjaxJson('-1', '操作失败');
        }
    }
}