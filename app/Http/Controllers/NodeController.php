<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Validator;
use App\Http\Models\NodeModel AS nodeDb;


class NodeController extends Common\Controller
{
    public function index()
    {
        return view('node.index');
    }

    public function getNode(Request $request)
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
        $total = nodeDb::where('pid', $pid)
                                ->count();
        //获取数据
        $result = nodeDb::select('id', 'name', 'alias', 'sort', 'status', 'icon', 'pid')
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

    //添加权限视图
    public function addNode(){
        //获取下拉菜单
        $result = nodeDb::select('id', 'name', 'alias', 'pid', 'sort')
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        $node['select'] = json_encode(sort_tree($result));
        return view('permission.addPermission', $node);
    }

    //添加权限
    public function createNode(Request $request)
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'node_name' => 'required|max:40',
            'node_alias' => 'required|max:90',
            'node_icon' => 'max:40',
            'node_sort' => 'required|max:3',
            'node_Fname' => 'required|max:255|numeric',
        ];
        $message = [
            'node_name.required' => '权限名称未填写',
            'node_alias.required' => '别名/地址未填写',
            'node_sort.required' => '排序未填写',
            'node_name.max' => '权限名称字符数过多',
            'node_alias.max' => '别名/地址字符数过多',
            'node_icon.max' => '图标字符数过多',
            'node_sort.max' => '排序字符数过多',
            'node_Fname.required' => '参数错误',
            'node_Fname.max' => '参数错误',
            'node_Fname.numeric' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('node.addNode'));
        }

        //格式化状态
        $input['node_status'] = array_key_exists('node_status', $input) ? 1 : 0;

        //添加数据
        $nodeDb = new nodeDb;
        $nodeDb->pid = $input['node_Fname'];
        $nodeDb->name = $input['node_name'];
        $nodeDb->alias = $input['node_alias'];
        $nodeDb->sort = $input['node_sort'];
        $nodeDb->icon = $input['node_icon'];
        $nodeDb->status = $input['node_status'];
        $nodeDb->save();

        if($nodeDb->save()){
            redirectPageMsg('1', "添加成功", route('node.addNode'));
        }else{
            redirectPageMsg('-1', "添加失败", route('node.addNode'));
        }
    }

    //编辑权限视图
    public function editNode(Request $request, $id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            redirectPageMsg('-1', '参数错误', route('node.index'));
        };

        //获取权限信息
        $node = nodeDb::select('id', 'name', 'alias', 'pid', 'sort', 'icon', 'status')
                                    ->where('id', $id)
                                    ->first()
                                    ->toArray();

        if(!$node){
            redirectPageMsg('1', "参数错误", route('node.index'));
        }

        //下拉菜单信息
        $result = nodeDb::select('id', 'name', 'alias', 'pid', 'sort')
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        $node['select'] = json_encode(sort_tree($result));
        return view('node.editNode', $node);
    }

    //编辑权限
    public function updateNode()
    {
        //验证表单
        $input = Input::all();
        //检测id类型是否整数
        if(!array_key_exists('node_id', $input)){
            redirectPageMsg('-1', '参数错误', route('node.index'));
        };
        $rules = [
            'node_name' => 'required|max:40',
            'node_alias' => 'required|max:90',
            'node_icon' => 'required|max:40',
            'node_sort' => 'required|max:3',
            'node_Fname' => 'required|max:255|numeric',
            'node_id' => 'required|max:255|numeric',
        ];
        $message = [
            'node_name.required' => '权限名称未填写',
            'node_alias.required' => '别名/地址未填写',
            'node_icon.required' => '图标未填写',
            'node_sort.required' => '排序未填写',
            'node_name.max' => '权限名称字符数过多',
            'node_alias.max' => '别名/地址字符数过多',
            'node_icon.max' => '图标字符数过多',
            'node_sort.max' => '排序字符数过多',
            'node_Fname.required' => '父级权限参数错误',
            'node_Fname.max' => '父级权限参数错误',
            'node_Fname.numeric' => '父级权限参数错误',
            'node_id.required' => '参数错误',
            'node_id.max' => '参数错误',
            'node_id.numeric' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('node.editNode')."/".$input['node_id']);
        }

        //格式化状态
        $input['node_status'] = array_key_exists('node_status', $input) ? 1 : 0;

        //格式化数据
        $data['pid'] = $input['node_Fname'];
        $data['name'] = $input['node_name'];
        $data['alias'] = $input['node_alias'];
        $data['sort'] = $input['node_sort'];
        $data['icon'] = $input['node_icon'];
        $data['status'] = $input['node_status'];
        //更新权限
        $result = nodeDb::where('id', $input['node_id'])
                                ->update($data);
        if($result){
            redirectPageMsg('1', "编辑成功", route('node.index'));
        }else{
            redirectPageMsg('-1', "编辑失败", route('node.editNode')."/".$input['node_id']);
        }
    }

    //删除权限
    public function delNode(){
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
        $children = nodeDb::where('pid', $id)
                                    ->where('status', '1')
                                    ->get()
                                    ->toArray();
        if($children){
            echoAjaxJson(0, '存在子项无法删除');
        }
        $rel = nodeDb::where('id', $id)
                                ->delete();
        if($rel){
            echoAjaxJson(1, '删除成功');
        }else{
            echoAjaxJson(0, '删除失败');
        }
    }
}