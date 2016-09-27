<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Validator;
use App\Http\Models\PermissionModel AS permissionDb;


class PermissionController extends Common\Controller
{
    public function index()
    {
        return view('permission.index');
    }

    public function getPermission(Request $request)
    {

        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }

        //获取参数
        $input = Input::all();
        $pid = isset($input['pid']) ? intval($input['pid']) : 0;

        //获取当前权限
        if($pid > 0){
            $permission = permissionDb::select('id', 'name', 'pid')
                ->where('id', $pid)
                ->first()
                ->toArray();
            $data['permission'] = $permission;
        }

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = permissionDb::where('pid', $pid)
                                ->count();
        //获取数据
        $result = permissionDb::select('id', 'name', 'alias', 'sort', 'status', 'icon', 'pid')
                                ->where('pid', $pid)
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

    //删除权限
    public function delPermission(){
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
        if($validator->fails()){
            echoAjaxJson(0, $validator->errors()->first());
        }
        $id = $input['id'];
       //查看是否存在子项
        $children = permissionDb::where('pid', $id)
                                    ->where('status', '1')
                                    ->get()
                                    ->toArray();
        if($children){
            echoAjaxJson(0, '存在子项无法删除');
        }
        $rel = permissionDb::where('id', $id)
                                ->delete();
        if($rel){
            echoAjaxJson(1, '删除成功');
        }else{
            echoAjaxJson(0, '删除失败');
        }
    }
}
