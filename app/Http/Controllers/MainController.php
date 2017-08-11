<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use App\Http\Models\Notice\NoticeModel AS NoticeDb;

class MainController extends Common\CommonController
{
    public function index()
    {
        return view('main.index');
    }
    
    //消息通知
    public function getMainNotice(Request $request)
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

        //获取记录总数
        $total = NoticeDb::where($searchSql)
            ->whereIn('notice_type', array('0','1'))->count();

        //获取数据
        $result = NoticeDb::where($searchSql)
            ->whereIn('notice_type', array('0','1'))
            ->select('notice_id', 'notice_message', 'created_at AS add_time', 'is_see AS see',
                'is_check AS check', 'notice_type AS type')
            ->limit(5)
            ->orderBy('is_check', 'asc')
            ->orderBy('is_see', 'asc')
            ->orderBy('notice_type', 'desc')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        //创建结果数据
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }
}
