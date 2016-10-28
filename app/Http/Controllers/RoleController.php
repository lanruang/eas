<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\RoleModel AS roleDb;
use App\Http\Models\PermissionModel AS permissionDb;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;

class RoleController extends Common\Controller
{
    public function index(){

        return view('role.index');
    }

    //获取角色列表
    public function getRole(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }

        //获取参数
        $input = Input::all();

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

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

        //返回结果
        ajaxJsonRes($data);
    }

    //添加角色视图
    public function addRole()
    {
        //获取权限列表
        $result = permissionDb::select('id', 'name', 'sort', 'pid')
            ->where('status', '1')
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        //树形排序
        $result = sort_tree($result);
        $data['data'] = json_encode($result);
        //p($result);
        return view('role.addRole', $data);
    }
}
