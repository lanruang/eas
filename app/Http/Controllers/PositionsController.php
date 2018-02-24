<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\Positions\PositionsModel AS PositionsDb;
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
        $total = PositionsDb::where('recycle', 0)->count();
        //获取数据
        $result = PositionsDb::select('pos_id AS id', 'pos_name AS name', 'pos_pid AS pid', 'status')
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
        return view('positions.addPositions');
    }

    //添加岗位
    public function createPositions()
    {
        //验证表单
        $input = Input::all();

        $rules = [
            'pos_name' => 'required|between:1,50',
            'pos_sort' => 'required|between:1,4|numeric',
            'pos_pid' => 'between:0,32',
        ];
        $message = [
            'pos_name.required' => '岗位名称未填写',
            'pos_name.between' => '岗位名称字符数过多',
            'pos_sort.required' => '排序未填写',
            'pos_sort.between' => '排序符数过多',
            'pos_sort.numeric' => '参数错误',
            'pos_pid.between' => '上级岗位参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('positions.addPositions'));
        }

        //岗位是否存在
        $result = PositionsDb::where('pos_name', $input['pos_name'])->first();
        if($result){
            return redirectPageMsg('-1', "添加失败，岗位名称重复", route('positions.addPositions'));
        }

        //格式化数据
        $input['pos_pid'] = !$input['pos_pid'] ? 0 : $input['pos_pid'];
        $input['pos_status'] = array_key_exists('pos_status', $input) ? 1 : 0;

        //创建员工
        $PositionsDb = new PositionsDb();
        $PositionsDb->pos_id = getId();
        $PositionsDb->pos_name = $input['pos_name'];
        $PositionsDb->pos_pid = $input['pos_pid'];
        $PositionsDb->sort = $input['pos_sort'];
        $PositionsDb->status = $input['pos_status'];
        $PositionsDb->recycle = 0;
        $result = $PositionsDb->save();

        if($result){
            return redirectPageMsg('1', "添加成功", route('positions.addPositions'));
        }else{
            return redirectPageMsg('-1', "添加失败", route('positions.addPositions'));
        }
    }
    
    //编辑岗位视图
    public function editPositions()
    {
        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.between' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('positions.index'));
        }
        $id = $input['id'];

        //获取岗位信息
        $positions = PositionsDb::select('pos_id AS id', 'pos_name AS name', 'pos_pid AS pid', 'sort', 'status')
            ->where('pos_id', $id)
            ->get()
            ->first()
            ->toArray();
        if(!$positions){
            return redirectPageMsg('-1', "参数错误", route('positions.index'));
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
        $data['pos'] = $positions;
        return view('positions.editPositions', $data);
    }
    
    //更新岗位信息
    public function updatePositions()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'pos_name' => 'required|between:1,50',
            'pos_sort' => 'required|between:1,4|numeric',
            'pos_pid' => 'between:0,32',
            'pos_id' => 'required|between:32,32',
        ];
        $message = [
            'pos_name.required' => '岗位名称未填写',
            'pos_name.between' => '岗位名称字符数过多',
            'pos_sort.required' => '排序未填写',
            'pos_sort.between' => '排序符数过多',
            'pos_sort.numeric' => '参数错误',
            'pos_pid.between' => '上级岗位参数错误',
            'pos_id.required' => '参数不存在',
            'pos_id.integer' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('positions.editPositions')."?id=".$input['pos_id']);
        }

        //格式化数据
        $data['pos_name'] = $input['pos_name'];
        $data['pos_pid'] = !$input['pos_pid'] ? 0 :$input['pos_pid'];
        $data['status'] = array_key_exists('pos_status', $input) ? 1 : 0;
        $data['sort'] = $input['pos_sort'];

        //岗位名称是否重复
        $result = PositionsDb::where('pos_name', $input['pos_name'])
                            ->where('pos_id', '<>', $input['pos_id'])
                            ->first();
        if($result){
            return redirectPageMsg('-1', "修改失败，岗位名称重复", route('positions.editPositions')."?id=".$input['pos_id']);
        }

        //更新数据
        $result = PositionsDb::where('pos_id', $input['pos_id'])
            ->update($data);

        if($result){
            return redirectPageMsg('1', "编辑成功", route('positions.index'));
        }else{
            return redirectPageMsg('-1', "编辑失败", route('positions.editPositions')."?id=".$input['pos_id']);
        }
    }

}
