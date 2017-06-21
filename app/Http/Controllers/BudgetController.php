<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\Budget\BudgetModel AS BudgetDb;
use App\Http\Models\Budget\BudgetSubjectModel AS BudgetSubjectDb;
use App\Http\Models\Budget\BudgetSubjectDateModel AS BudgetSubjectDateDb;
use Illuminate\Support\Facades\DB;

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

    //添加预算视图
    public function addBudget()
    {
        //查询在编辑状态
        $result = BudgetDb::where('status', '102')->first();
        if($result){
            return redirectPageMsg('-1', "无法添加预算，已存在编辑状态", route('budget.index'));
        }

        return view('budget.addBudget');
    }

    //添加预算
    public function createBudget()
    {
        //验证表单
        $input = Input::all();

        $rules = [
            'budget_num' => 'required|between:1,200',
            'budget_name' => 'required|between:1,200',
            'budget_date' => 'required',
        ];
        $message = [
            'budget_num.required' => '请填写预算编号',
            'budget_num.between' => '预算编号字符数超出范围',
            'budget_name.required' => '请填写预算名称',
            'budget_name.between' => '预算名称字符数超出范围',
            'budget_date.required' => '请选择预算期间',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('budget.addBudget'));
        }

        //检查预算编号是否存在
        $result = BudgetDb::where('budget_num', $input['budget_num'])->first();
        if($result){
            return redirectPageMsg('-1', "添加失败，预算编号存在", route('budget.addBudget'));
        }

        //格式化日期数据
        $date = explode(' 一 ', $input['budget_date']);
        if(count($date) != '2'){
            return redirectPageMsg('-1', "添加失败，预算期间错误", route('budget.addBudget'));
        }
        if(!strtotime($date[0]) || !strtotime($date[1])){
            return redirectPageMsg('-1', "添加失败，预算期间错误", route('budget.addBudget'));
        }

        $input['budget_start'] = $date[0];
        $input['budget_end'] = $date[1];
        $input['status'] = 102;

        //创建预算
        $budgetDb = new BudgetDb();
        $budgetDb->budget_num = $input['budget_num'];
        $budgetDb->budget_name = $input['budget_name'];
        $budgetDb->budget_start = $input['budget_start'];
        $budgetDb->budget_end = $input['budget_end'];
        $budgetDb->status = $input['status'];
        $result = $budgetDb->save();

        if($result){
            return redirectPageMsg('1', "添加成功，将添加预算项", route('budget.addBudget'));
        }else{
            return redirectPageMsg('-1', "添加失败", route('budget.addBudget'));
        }
    }

    //编辑预算视图
    public function editBudget($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            return redirectPageMsg('-1', '参数错误', route('budget.index'));
        };

        //获取预算信息
        $budget = BudgetDb::where('budget_id', $id)
            ->get()
            ->first()
            ->toArray();
        if(!$budget){
            return redirectPageMsg('-1', "参数错误", route('budget.index'));
        }

        return view('budget.editBudget', $budget);
    }

    //编辑预算
    public function updateBudget()
    {
        //验证表单
        $input = Input::all();
        //检测id是否存在
        if(!array_key_exists('id', $input)){
            return redirectPageMsg('-1', '参数错误', route('budget.index'));
        };
        $rules = [
            'budget_num' => 'required|between:1,200',
            'budget_name' => 'required|between:1,200',
            'budget_date' => 'required',
        ];
        $message = [
            'budget_num.required' => '请填写预算编号',
            'budget_num.between' => '预算编号字符数超出范围',
            'budget_name.required' => '请填写预算名称',
            'budget_name.between' => '预算名称字符数超出范围',
            'budget_date.required' => '请选择预算期间',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('budget.editBudget')."/".$input['id']);
        }

        //检查预算编号是否存在
        $result = BudgetDb::where('budget_num', $input['budget_num'])
                        ->where('budget_id', '<>', $input['id'])
                        ->first();
        if($result){
            return redirectPageMsg('-1', "编辑失败，预算编号存在", route('budget.editBudget')."/".$input['id']);
        }

        //格式化日期数据
        $date = explode(' 一 ', $input['budget_date']);
        if(count($date) != '2'){
            return redirectPageMsg('-1', "编辑失败，预算期间错误", route('budget.editBudget')."/".$input['id']);
        }
        if(!strtotime($date[0]) || !strtotime($date[1])){
            return redirectPageMsg('-1', "编辑失败，预算期间错误", route('budget.editBudget')."/".$input['id']);
        }

        $input['budget_start'] = $date[0];
        $input['budget_end'] = $date[1];

        //更新预算数据
        $data['budget_num'] = $input['budget_num'];
        $data['budget_name'] = $input['budget_name'];
        $data['budget_end'] = $input['budget_end'];

        //更新预算
        $result = BudgetDb::where('budget_id', $input['id'])
                        ->update($data);
        if($result){
            return redirectPageMsg('1', "编辑成功", route('budget.index'));
        }else{
            return redirectPageMsg('-1', "编辑失败", route('budget.editBudget')."/".$input['id']);
        }
    }

    //添加预算项视图
    public function addBudgetSub($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            return redirectPageMsg('-1', '参数错误', route('budget.index'));
        };

        //获取预算信息
        $budget = BudgetDb::where('budget_id', $id)
            ->get()
            ->first();
        if(!$budget){
            return redirectPageMsg('-1', "参数错误", route('budget.index'));
        }

        return view('budget.addBudgetSub', $budget);
    }

    //获取预算项目
    public function getBudgetSub(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();
        $rules = [
            'budget_id' => 'required|digits_between:1,11',
        ];
        $message = [
            'budget_id.required' => '参数不存在',
            'budget_id.digits_between' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }

        /*
         * //获取数据
        $result = BudgetSubjectDb::leftjoin('subjects AS sj', 'BudgetS.subject_id', '=', 'sj.sub_id')
                            ->select('BudgetS.budget_id', 'BudgetS.subject_id AS sub_id', 'sj.sub_name AS subject',
                            'BudgetS.status')
                            ->where('BudgetS.budget_id', $input['budget_id'])
                            ->get()
                            ->toArray();
         * */


        //获取数据
        $result = BudgetSubjectDb::leftjoin('budget_subject_date AS bsd', function ($join) {
                                    $join->on('BudgetS.budget_id', '=', 'bsd.budget_id')
                                    ->on('BudgetS.subject_id', '=', 'bsd.subject_id');})
                            ->leftjoin('subjects AS sj', 'BudgetS.subject_id', '=', 'sj.sub_id')
                            ->select('BudgetS.budget_id',
                                'BudgetS.subject_id AS sub_id',
                                'BudgetS.status',
                                'sj.sub_name AS subject',
                                DB::raw('SUM(bsd.budget_amount) AS budget_amount')
                            )
                            ->where('BudgetS.budget_id', $input['budget_id'])
                            ->get()
                            ->toArray();

        //创建结果数据
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }
}
