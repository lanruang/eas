<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use App\Http\Models\Notice\NoticeModel AS NoticeDb;
use App\Http\Models\Expense\ExpenseModel AS ExpenseDb;
use App\Http\Models\Expense\ExpenseMainModel AS ExpenseMainDb;
use Illuminate\Support\Facades\DB;
use Validator;

class NoticeController extends Common\CommonController
{
    public function index()
    {
        return view('notice.index');
    }

    //获取报销列表
    public function getNotice(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //搜索参数
        $searchSql[] = array('notice_user', session('userInfo.user_id'));

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = NoticeDb::where($searchSql)->count();

        //获取数据
        $result = NoticeDb::where($searchSql)
            ->select('notice_id', 'notice_message', 'created_at AS add_time', 'is_see AS see')
            ->skip($skip)
            ->take($take)
            ->orderBy('created_at', 'desc')
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
    
    //阅读通知
    public function noticeRead()
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('notice.index'));
        }
        $id = $input['id'];

        //获取消息
        $result = NoticeDb::from('notice AS not')
            ->leftjoin('users AS u', 'u.user_id', '=', 'not.post_user')
            ->leftjoin('users_base AS ub', 'u.user_id', '=', 'ub.user_id')
            ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->select('not.notice_id', 'not.notice_message', 'u.user_name', 'not.created_at',
                'not.post_user', 'not.is_see', 'pos.pos_name')
            ->where('not.notice_id', $id)
            ->where('not.notice_user', session('userInfo.user_id'))
            ->get()
            ->first();
        if(!$result){
            return redirectPageMsg('-1', '内容不存在', route('notice.index'));
        }
     
        if($result['is_see'] == '0'){
            //更新查看点击
            NoticeDb::where('notice_id', $id)
                ->where('notice_user', session('userInfo.user_id'))
                ->update(array('is_see'=>1));
        }
        return view('notice.noticeRead', $result);
    }
}
