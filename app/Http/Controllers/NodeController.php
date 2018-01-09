<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\Node\NodeModel AS nodeDb;
use Illuminate\Contracts\Encryption\DecryptException;

class NodeController extends Common\CommonController
{
    public function index()
    {
        return view('node.index');
    }

    public function getNode()
    {
        //获取参数
        $input = Input::all();
        $pid = isset($input['pid']) ? $input['pid'] : '0';

        //获取当前权限
        if($pid != '0'){
            $node = nodeDb::select('id', 'name', 'pid')
                ->where('id', $pid)
                ->first()
                ->toArray();
            //敏感参数处理
            $data['node'] = $node;
        }

        //分页
        $take = !empty($input['length']) ? intval($input['length']) : 10;//数据长度
        $skip = !empty($input['start']) ? intval($input['start']) : 0;//从多少开始

        //获取记录总数
        $total = nodeDb::where('pid', $pid)
                                ->count();
        //获取数据
        $result = nodeDb::where('pid', $pid)
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
        $data['status'] = '1';

        //返回结果
        ajaxJsonRes($data);
    }

    //添加权限视图
    public function addNode(){
        //获取下拉菜单
        $result = nodeDb::select('id', 'name AS text', 'alias', 'pid')
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = nodeDb::where('status', 1)
            ->min('pid');
        $result = !getTree($result, $selectPid) ? $result = array() : getTree($result, $selectPid);

        $node['select'] = json_encode($result);
        return view('node.addNode', $node);
    }

    //添加权限
    public function createNode()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'node_name' => 'required|max:40',
            'node_alias' => 'required|max:90',
            'node_icon' => 'max:40',
            'node_sort' => 'required|digits_between:1,4',
            'node_pid' => 'between:32,32',
            'recycle_name' => 'required_with:recycle|max:50',
            'recycle_type' => 'required_with:recycle|max:40'
        ];
        $message = [
            'node_name.required' => '权限名称未填写',
            'node_name.max' => '权限名称字符数过多',
            'node_alias.required' => '别名/地址未填写',
            'node_alias.max' => '别名/地址字符数过多',
            'node_sort.required' => '排序未填写',
            'node_sort.digits_between' => '排序字符数过多',
            'node_icon.max' => '图标字符数过多',
            'node_pid.digits_between' => '父级权限参数错误',
            'recycle_name.required_with' => '回收站名称必须填写',
            'recycle_name.max' => '回收站名称字符数过多',
            'recycle_type.required_with' => '回收站分类必须填写',
            'recycle_type.max' => '回收站分类字符数过多'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('node.addNode'));
        }

        //格式化状态
        $input['node_status'] = array_key_exists('node_status', $input) ? 1 : 0;
        $input['node_is_menu'] = array_key_exists('node_is_menu', $input) ? 1 : 0;
        $input['node_is_permission'] = array_key_exists('node_is_permission', $input) ? 1 : 0;
        $input['is_recycle'] = array_key_exists('recycle', $input) ? 1 : 0;

        //添加数据
        $nodeDb = new nodeDb;
        $nodeDb->id = getId();
        $nodeDb->pid = $input['node_pid'];
        $nodeDb->name = $input['node_name'];
        $nodeDb->alias = $input['node_alias'];
        $nodeDb->sort = $input['node_sort'];
        $nodeDb->icon = $input['node_icon'];
        $nodeDb->status = $input['node_status'];
        $nodeDb->is_menu = $input['node_is_menu'];
        $nodeDb->is_permission = $input['node_is_permission'];
        $nodeDb->is_recycle = $input['is_recycle'];
        $nodeDb->recycle_name = $input['recycle_name'];
        $nodeDb->recycle_type = $input['recycle_type'];
        $result = $nodeDb->save();

        if($result){
            return redirectPageMsg('1', "添加成功", route('node.addNode'));
        }else{
            return redirectPageMsg('-1', "添加失败", route('node.addNode'));
        }
    }

    //编辑权限视图
    public function editNode()
    {
        //获取参数
        $input = Input::all();
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数错误，请刷新后重试',
            'id.max' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('node.index'));
        }
        $id = $input['id'];
        //获取权限信息
        $node = nodeDb::leftjoin('node AS nd', 'node.pid','=','nd.id')
                            ->select('node.id', 'node.name', 'node.alias', 'node.pid',
                                'node.sort', 'node.icon', 'node.status', 'node.is_menu',
                                'nd.name AS ndName', 'nd.alias AS ndAlias', 'node.is_recycle',
                                'node.recycle_type', 'node.recycle_name', 'node.is_permission')
                            ->where('node.id', $id)
                            ->first()
                            ->toArray();
        if(!$node){
            return redirectPageMsg('-1', "权限获取失败", route('node.index'));
        }

        //下拉菜单信息
        $result = nodeDb::select('id', 'name AS text', 'alias', 'pid')
                            ->orderBy('sort', 'asc')
                            ->get()
                            ->toArray();
        //获取下拉菜单最小pid
        $selectPid = nodeDb::where('status', 1)
            ->min('pid');
        $result = !getTree($result, $selectPid) ? $result = array() : getTree($result, $selectPid);
         
        $node['select'] = json_encode($result);
        return view('node.editNode', $node);
    }

    //编辑权限
    public function updateNode()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'node_name' => 'required|max:40',
            'node_alias' => 'required|max:90',
            'node_icon' => 'max:40',
            'node_sort' => 'required|digits_between:1,4',
            'node_pid' => 'between:32,32',
            'node_id' => 'required|between:32,32',
            'recycle_name' => 'required_with:recycle|max:50',
            'recycle_type' => 'required_with:recycle|max:40'
        ];
        $message = [
            'node_name.required' => '权限名称未填写',
            'node_name.max' => '权限名称字符数过多',
            'node_alias.required' => '别名/地址未填写',
            'node_alias.max' => '别名/地址字符数过多',
            'node_sort.required' => '排序未填写',
            'node_sort.digits_between' => '排序字符数过多',
            'node_icon.max' => '图标字符数过多',
            'node_pid.between' => '父级权限参数错误',
            'node_id.required' => '缺少必要参数',
            'node_id.between' => '参数错误',
            'recycle_name.required_with' => '回收站名称必须填写',
            'recycle_name.max' => '回收站名称字符数过多',
            'recycle_type.required_with' => '回收站分类必须填写',
            'recycle_type.max' => '回收站分类字符数过多'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('node.editNode')."/".$input['node_id']);
        }
        //格式化状态
        $input['node_status'] = array_key_exists('node_status', $input) ? 1 : 0;
        $input['node_is_menu'] = array_key_exists('node_is_menu', $input) ? 1 : 0;
        $input['node_is_permission'] = array_key_exists('node_is_permission', $input) ? 1 : 0;
        $input['is_recycle'] = array_key_exists('recycle', $input) ? 1 : 0;

        //格式化数据
        $data['pid'] = $input['node_pid'];
        $data['name'] = $input['node_name'];
        $data['alias'] = $input['node_alias'];
        $data['sort'] = $input['node_sort'];
        $data['icon'] = $input['node_icon'];
        $data['status'] = $input['node_status'];
        $data['is_menu'] = $input['node_is_menu'];
        $data['is_permission'] = $input['node_is_permission'];
        $data['is_recycle'] = $input['is_recycle'];
        $data['recycle_name'] = $input['recycle_name'];
        $data['recycle_type'] = $input['recycle_type'];
        //更新权限
        $result = nodeDb::where('id', $input['node_id'])
                                ->update($data);
        if($result){
            return redirectPageMsg('1', "编辑成功", route('node.index'));
        }else{
            return redirectPageMsg('-1', "编辑失败", route('node.editNode')."?id=".$input['node_id']);
        }
    }

    //删除权限
    public function delNode(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }
        $input = Input::all();
        
        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }
        $id = $input['id'];
       //查看是否存在子项
        $children = nodeDb::where('pid', $id)
                                    ->where('status', '1')
                                    ->get()
                                    ->toArray();
        if($children){
            echoAjaxJson('-1', '存在子项无法删除');
        }
        $rel = nodeDb::where('id', $id)
                                ->delete();
        if($rel){
            echoAjaxJson('1', '删除成功');
        }else{
            echoAjaxJson('-1', '删除失败');
        }
    }

    //更新排序
    public function updateSort(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            ajaxJsonRes(array("error"=>"非法请求"));
        }

        $input = Input::all();

        //格式化参数
        foreach($input['data'] as $k => $v){
            foreach($v as $vk => $vv){
                $input['id'] = $k;
                $input['sort'] = $vv;
            }
        }

        //过滤信息
        $rules = [
            'id' => 'required|integer|between:32,32',
            'sort' => 'required|integer|digits_between:1,4',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.integer' => '参数类型错误',
            'id.digits_between' => '参数错误',
            'sort.required' => '必填项',
            'sort.integer' => '必须为数字',
            'sort.digits_between' => '超出最大值'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            ajaxJsonRes(array("error"=>$validator->errors()->first()));
        }

        $data = nodeDb::select('id', 'name', 'alias', 'sort', 'status', 'icon', 'pid', 'is_menu')
            ->where('id', $input['id'])
            ->get()
            ->toArray();

        if(!$data){
            ajaxJsonRes(array("error"=>"更新失败"));
        }

        //更新权限
        $result = nodeDb::where('id', $input['id'])
            ->update(array('sort'=>$input['sort']));
        $data[0]['sort'] = $input['sort'];
        $json['data']['sort'] = $input['sort'];

        if($result){
            ajaxJsonRes($json);
        }else{
            ajaxJsonRes(array("error"=>"更新失败"));
        }
    }
}
