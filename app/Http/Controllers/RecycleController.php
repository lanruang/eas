<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;

class RecycleController extends Common\CommonController
{
    public function index()
    {
        p(session('userInfo.recycle'));
        return view('recycle.index');
    }

    public function getRecycle(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            //echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        $this->departmentRecycle();

        /*
        //创建结果数据
        $data['draw'] = isset($input['draw']) ? intval($input['draw']) : 1;
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;
        */

        //返回结果
        //ajaxJsonRes($data);
    }

    private function departmentRecycle(){
        //获取记录总数
        $total = DepartmentDb::where('recycle',0)->count();
        //获取数据
        $result = DepartmentDb::leftjoin('users', 'users.user_id', '=', 'dep_leader')
            ->select('dep_id AS id', 'dep_name AS name', 'dep_pid AS pid',  'user_name AS u_name')
            ->where('department.recycle', 0)
            ->orderBy('sort', 'ASC')
            ->get()
            ->toArray();
        $result = sortTree($result);
    }

}
