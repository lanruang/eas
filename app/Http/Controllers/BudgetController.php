<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use App\Http\Models\BudgetModel AS BudgetDb;

class BudgetController extends Common\CommonController
{
    public function index()
    {
        return view('budget.index');
    }

    //预算列表
    public function getBudget(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();
        

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = BudgetDb::count();
        //获取数据
        $result = BudgetDb::select('budget_id AS id', 'budget_num AS bd_num', 'budget_name AS bd_name',
                                'budget_start AS bd_start', 'budget_end AS bd_end' , 'status')
            ->skip($skip)
            ->take($take)
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

    //添加预算
    public function addBudget()
    {
        return view('budget.addBudget');
    }
}
