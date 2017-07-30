<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\Role\RoleModel AS roleDb;
use App\Http\Models\Node\NodeModel AS nodeDb;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Input;

class RoleController extends Common\CommonController
{
    public function index()
    {
        return view('role.index');
    }

    //获取角色列表
    public function getRole(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //分页
        $skip = !empty($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = !empty($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = roleDb::count();
        //获取数据
        $result = roleDb::select('id', 'name', 'sort', 'status')
            ->skip($skip)
            ->take($take)
            ->orderBy('sort', 'asc')
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

    //添加角色视图
    public function addRole()
    {
        //获取权限列表
        $result = nodeDb::select('id', 'name', 'sort', 'pid')
            ->where('status', '1')
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        //树形排序
        $result = sortTree($result);
        $data['data'] = json_encode($result);
        return view('role.addRole', $data);
    }

    //添加角色
    public function createRole()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'role_name' => 'required|between:1,40',
            'role_sort' => 'required|between:1,4',
        ];
        $message = [
            'node_name.required' => '角色名称未填写',
            'node_sort.required' => '排序未填写',
            'node_sort.between' => '排序字符数过多',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('role.addRole'));
        }

        //格式化状态
        $input['role_status'] = array_key_exists('role_status', $input) ? 1 : 0;

        //格式化权限
        $node = array_key_exists('node', $input) ? $input['node'] : '';

        //事务
        $result = DB::transaction(function () use($input, $node) {
            //初始化数据
            $roleDb = new roleDb();
            $roleDb->name = $input['role_name'];
            $roleDb->sort = $input['role_sort'];
            $roleDb->status = $input['role_status'];
            $roleDb->save();
            //创建角色
            $role_id = $roleDb->id;
            //判断权限变动
            if($node){
                foreach($node as $k => $v){
                    $role_data[$k]['role_id'] = $role_id;
                    $role_data[$k]['node_id'] = $v;
                }
                //创建权限
                DB::table('role_node')->insert($role_data);
            }
            return true;
        });

        if($result){
            return redirectPageMsg('1', "添加成功", route('role.addRole'));
        }else{
            return redirectPageMsg('-1', "添加失败", route('role.addRole'));
        }
    }

    //编辑权限视图
    public function editRole($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            return redirectPageMsg('-1', '参数错误', route('role.index'));
        };

        //获取角色信息
        $role = roleDb::select('id', 'name', 'sort', 'status')
                        ->where('id', $id)
                        ->first()
                        ->toArray();
        //获取权限
        $role_node = DB::table('role_node')
                            ->where('role_id', $id)
                            ->get();
        foreach($role_node as $v){
            $role['node'][] = $v->node_id;
        }
        $role['node'] = implode(',', $role['node']);

        if(!$role){
            return redirectPageMsg('-1', "参数错误", route('role.index'));
        }
        $role['node'] = json_encode(explode(',', $role['node']));

        //获取权限列表
        $result = nodeDb::select('id', 'name', 'sort', 'pid')
                    ->where('status', '1')
                    ->orderBy('sort', 'asc')
                    ->get()
                    ->toArray();
        //树形排序
        $result = sortTree($result);
        $role['select'] = json_encode(sortTree($result));

        return view('role.editRole', $role);
    }

    //更新角色
    public function updateRole()
    {
        //验证表单
        $input = Input::all();
        //检测id类型是否整数
        if(!array_key_exists('role_id', $input)){
            return redirectPageMsg('-1', '参数错误', route('role.index'));
        };
        $rules = [
            'role_name' => 'required|between:1,40',
            'role_sort' => 'required|between:1,4',
            'role_id' =>  'between:1,11'
        ];
        $message = [
            'role_name.required' => '权限名称未填写',
            'role_sort.required' => '排序未填写',
            'role_name.between' => '权限名称字符数过多',
            'role_sort.between' => '排序字符数过多',
            'role_id.required' => '参数错误',
            'role_id.between' => '参数错误',
            'role_id.numeric' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('role.editRole')."/".$input['role_id']);
        }

        //格式化状态
        $input['role_status'] = array_key_exists('role_status', $input) ? 1 : 0;

        //格式化权限
        $node = array_key_exists('node', $input) ? $input['node'] : '';

        //事务
        $result = DB::transaction(function () use($input, $node) {
            //格式化数据
            $data['name'] = $input['role_name'];
            $data['sort'] = $input['role_sort'];
            $data['status'] = $input['role_status'];
            //更新角色信息
            roleDb::where('id', $input['role_id'])->update($data);
            //判断权限变动
            if($node){
                foreach($node as $k => $v){
                    $role_data[$k]['role_id'] = $input['role_id'];
                    $role_data[$k]['node_id'] = $v;
                }
                //删除历史权限
                DB::table('role_node')->where('role_id', $input['role_id'])->delete();
                //创建权限
                DB::table('role_node')->insert($role_data);
            }
            return true;
        });

        if($result){
            return redirectPageMsg('1', "编辑成功", route('role.index'));
        }else{
            return redirectPageMsg('-1', "编辑失败", route('role.editRole')."/".$input['role_id']);
        }
    }

    //删除角色
    public function delRole(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }
        $input = Input::all();

        //过滤信息
        $rules = [
            'id' => 'required|integer|between:1,11',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.integer' => '参数类型错误',
            'id.between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            echoAjaxJson('-1', $validator->errors()->first());
        }
        $id = $input['id'];

        $result = DB::transaction(function () use($id) {
            $role = roleDb::where('id', $id)
                ->delete();
            $role_node = DB::table('role_node')->where('role_id', $id)
                ->delete();
            if($role && $role_node){
                return true;
            }else{
                return false;
            }
        });

        if ($result) {
            echoAjaxJson('1', '删除成功');
        } else {
            echoAjaxJson('-1', '删除失败');
        }
    }

    //角色详情
    public function roleInfo($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            return redirectPageMsg('-1', '参数错误', route('role.index'));
        };

        //获取角色信息
        $role = roleDb::select('id', 'name', 'sort', 'status')
            ->where('id', $id)
            ->first()
            ->toArray();
        //获取权限
        $role_node = DB::table('role_node')
            ->where('role_id', $id)
            ->get();
        foreach($role_node as $v){
            $role['node'][] = $v->node_id;
        }
        $role['node'] = implode(',', $role['node']);

        if(!$role){
            return redirectPageMsg('-1', "参数错误", route('role.index'));
        }
        $role['node'] = json_encode(explode(',', $role['node']));

        //获取权限列表
        $result = nodeDb::select('id', 'name', 'sort', 'pid')
                    ->where('status', '1')
                    ->orderBy('sort', 'asc')
                    ->get()
                    ->toArray();
        //树形排序
        $result = sortTree($result);
        $role['select'] = json_encode(sortTree($result));

        return view('role.roleInfo', $role);
    }
}
