<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\PositionsModel AS PositionsDb;
use Illuminate\Support\Facades\Input;
use Validator;

class PositionsController extends Common\CommonController
{
    //岗位列表
    public function index()
    {
        return view('positions.index');
    }

    //岗位列表
    public function getPositions(Request $request){
        //验证传输方式

        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = PositionsDb::count();
        //获取数据
        $result = PositionsDb::select('pos_id AS id', 'pos_name AS name', 'pos_pid AS pid', 'is_del AS deleted')
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

    //添加岗位视图
    public function addPositions()
    {
        //获取下拉菜单
        $result = PositionsDb::select('pos_id AS id', 'pos_name AS text', 'pos_pid AS pid')
            ->where('is_del', 0)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();

        $result = !$result ? $result = array() : getTreeT($result);
        $data['select'] = json_encode($result);
        return view('positions.addPositions', $data);
    }

    //添加岗位
    public function createPositions()
    {
        //验证表单
        $input = Input::all();

        $rules = [
            'pos_name' => 'required|between:1,50',
            'pos_sort' => 'required|between:1,4|numeric',
            'pos_pid' => 'between:0,11|numeric',
        ];
        $message = [
            'pos_name.required' => '岗位名称未填写',
            'pos_name.between' => '岗位名称字符数过多',
            'pos_sort.required' => '排序未填写',
            'pos_sort.between' => '排序符数过多',
            'pos_sort.numeric' => '参数错误',
            'pos_pid.between' => '上级岗位参数错误',
            'pos_pid.numeric' => '上级岗位参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('Positions.addPositions'));
        }

        //岗位是否存在
        $result = PositionsDb::where('pos_name', $input['pos_name'])->first();
        if($result){
            redirectPageMsg('-1', "添加失败，岗位名称重复", route('positions.addPositions'));
        }

        //格式化数据
        $input['pos_pid'] = !$input['pos_pid'] ? 0 : $input['pos_pid'];

        //创建员工
        $PositionsDb = new PositionsDb();
        $PositionsDb->pos_name = $input['pos_name'];
        $PositionsDb->pos_pid = $input['pos_pid'];
        $PositionsDb->sort = $input['pos_sort'];
        $PositionsDb->is_del = 0;
        $result = $PositionsDb->save();

        if($result){
            redirectPageMsg('1', "添加成功", route('positions.addPositions'));
        }else{
            redirectPageMsg('-1', "添加失败", route('positions.addPositions'));
        }
    }
    
    //编辑岗位视图
    public function editPositions($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            redirectPageMsg('-1', '参数错误', route('positions.index'));
        };

        //获取岗位信息
        $positions = PositionsDb::select('pos_id AS id', 'pos_name AS name', 'pos_pid AS pid', 'sort')
            ->where('pos_id', $id)
            ->get()
            ->first()
            ->toArray();
        if(!$positions){
            redirectPageMsg('-1', "参数错误", route('positions.index'));
        }
        $positions['p_name'] = '';
        //获取上级岗位
        if($positions['pid'] > 0){
            $positionsP = PositionsDb::select('pos_name AS name')
                ->where('pos_id', $positions['pid'])
                ->get()
                ->first();
            if($positionsP){
                $positions['p_name'] = $positionsP->name;
            }
        }

        //获取下拉菜单
        $result = PositionsDb::select('pos_id AS id', 'pos_name AS text', 'pos_pid AS pid')
            ->where('is_del', 0)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();

        $result = !$result ? $result = array() : getTreeT($result);
        $data['select'] = json_encode($result);
        $data['pos'] = $positions;

        return view('positions.editPositions', $data);
    }
    
    //更新岗位信息
    public function updatePositions()
    {
        //验证表单
        $input = Input::all();
        //检测id类型是否整数
        if(!array_key_exists('pos_id', $input)){
            redirectPageMsg('-1', '参数错误', route('positions.index'));
        };

        $rules = [
            'pos_name' => 'required|between:1,50',
            'pos_sort' => 'required|between:1,4|numeric',
            'pos_pid' => 'between:0,11|numeric',
        ];
        $message = [
            'pos_name.required' => '岗位名称未填写',
            'pos_name.between' => '岗位名称字符数过多',
            'pos_sort.required' => '排序未填写',
            'pos_sort.between' => '排序符数过多',
            'pos_sort.numeric' => '参数错误',
            'pos_pid.between' => '上级岗位参数错误',
            'pos_pid.numeric' => '上级岗位参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('positions.editPositions')."/".$input['pos_id']);
        }

        //岗位是否存在
        $result = PositionsDb::where('pos_name', $input['pos_name'])->first();
        if($result){
            redirectPageMsg('-1', "修改失败，岗位名称重复", route('positions.editPositions')."/".$input['pos_id']);
        }

        //格式化数据
        $data['pos_name'] = $input['pos_name'];
        $data['pos_pid'] = !$input['pos_pid'] ? 0 :$input['pos_pid'];
        $data['sort'] = $input['pos_sort'];

        //更新数据
        $result = PositionsDb::where('pos_id', $input['pos_id'])
            ->update($data);

        if($result){
            redirectPageMsg('1', "编辑成功", route('positions.index'));
        }else{
            redirectPageMsg('-1', "编辑失败", route('positions.editPositions')."/".$input['pos_id']);
        }
    }

    //删除岗位
    public function delPositions(Request $request)
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
        $result = PositionsDb::where('pos_id', $input['id'])
            ->update($data);

        if ($result) {
            echoAjaxJson('1', '操作成功');
        } else {
            echoAjaxJson('-1', '操作失败');
        }
    }
}
