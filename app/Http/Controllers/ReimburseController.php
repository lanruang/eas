<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Expense\ExpenseModel AS ExpenseDb;
use App\Http\Models\Expense\ExpenseMainModel AS ExpenseMainDb;
use App\Http\Models\Expense\ExpEnclosureModel AS ExpEnclosureDb;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Input;
use Storage;

class ReimburseController extends Common\CommonController
{
    public function index()
    {
        return view('reimburse.index');
    }
    
    //获取报销列表
    public function getReimburse(Request $request)
    {/*
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }
*/
        //获取参数
        $input = Input::all();

        //搜索参数
        $searchSql[] = array('exp.expense_type', '=', 'reimburse');

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = ExpenseDb::from('expense AS exp')->where($searchSql)->count();
  
        //获取数据
        $result = ExpenseDb::from('expense AS exp')
            ->leftJoin('department AS dep', 'dep.dep_id','=','exp.expense_dep')
            ->leftJoin('users AS u', 'u.user_id','=','exp.expense_user')
            ->select('u.user_name AS user_name', 'dep.dep_name AS dep_name', 'exp.expense_num AS exp_num', 'exp.expense_id AS exp_id',
                'exp.expense_title AS exp_title', 'exp.expense_date AS exp_date', 'exp.expense_status AS exp_status')
            ->where($searchSql)
            ->skip($skip)
            ->take($take)
            ->orderBy('exp.expense_status', 'desc')
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
    
    //添加报销单据视图
    public function addReimburse()
    {
        //获取编辑状态单据
        $result = ExpenseDb::where('expense_user', session('userInfo.user_id'))
            ->where('expense_status', '202')
            ->where('expense_type', 'reimburse')
            ->get()
            ->first();

        //存在输入否则添加
        if($result){
            //如果部门不同则更新部门
            if($result->dep_id != session('userInfo.dep_id')){
                ExpenseDb::where('expense_user', session('userInfo.user_id'))
                    ->where('expense_status', '202')
                    ->where('expense_type', 'reimburse')
                    ->update(['expense_dep' => session('userInfo.dep_id')]);
            }
            $result['expense_dep'] = session('userInfo.dep_id');
            $data = $result;
        }else{
            //查询单号是否存在
             do {
                $expense_num = 'R'.date('YmdHis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $rel = ExpenseDb::where('expense_num', $expense_num)
                    ->get()
                    ->first();
            } while($rel);

            //创建单号
            $expenseDb = new ExpenseDb();
            $expenseDb['expense_type'] = 'reimburse';//费用报销
            $expenseDb['expense_dep'] = session('userInfo.dep_id');//部门id
            $expenseDb['expense_user'] = session('userInfo.user_id');//用户id
            $expenseDb['expense_num'] = $expense_num;
            $expenseDb['expense_title'] = '';//副标题
            $expenseDb['expense_date'] = date('Y-m-d', time());//日期
            $expenseDb['expense_doc_num'] = 0;//明细数
            $expenseDb['expense_amount'] = 0;//合计金额
            $expenseDb['expense_status'] = '202';//状态
            $expenseDb->save();
            $data = $expenseDb;
        }
        $data['user_name'] = session('userInfo.user_name');
        $data['dep_name'] = session('userInfo.dep_name');

        //获取明细
        $data['expMain'] = ExpenseMainDb::where('expense_id', $data['expense_id'])
            ->select('exp_remark', 'exp_amount', 'enclosure')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        return view('reimburse.addReimburse', $data);
    }

    //编辑报销单据视图
    public function editReimburse($id = 0)
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            return redirectPageMsg('-1', '参数错误', route('reimburse.index'));
        };

        //获取编辑状态单据
        $data = ExpenseDb::from('expense AS exp')
            ->leftJoin('department AS dep', 'dep.dep_id','=','exp.expense_dep')
            ->leftJoin('users AS u', 'u.user_id','=','exp.expense_user')
            ->select('u.user_name AS user_name', 'dep.dep_name AS dep_name', 'exp.expense_num', 'exp.expense_id',
                'exp.expense_title', 'exp.expense_date', 'exp.expense_status')
            ->where('exp.expense_status', '202')
            ->where('exp.expense_type', 'reimburse')
            ->get()
            ->first();
        if(!$data){
            return redirectPageMsg('-1', '单据信息获取失败，请刷新后重试', route('reimburse.index'));
        }
        //获取明细
        $data['expMain'] = ExpenseMainDb::where('expense_id', $id)
            ->select('exp_remark', 'exp_amount', 'enclosure')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        return view('reimburse.addReimburse', $data);
    }

    //更新表头信息
    public function updateExpense(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            ajaxJsonRes(array("error"=>"非法请求"));
        }

        $input = Input::all();
        $rules = [
            'exp_id' => 'required|digits_between:1,11',
            'exp_date' => 'required|date',
        ];
        $message = [
            'exp_id.required' => '缺少必要参数',
            'exp_id.digits_between' => '参数错误',
            'exp_date.required' => '日期获取失败',
            'exp_date.date' => '日期格式错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }

        $data['expense_title'] = $input['exp_title'];
        $data['expense_date'] = $input['exp_date'];

        $result = ExpenseDb::where('expense_id', $input['exp_id'])
            ->update($data);
        if($result){
            echoAjaxJson('1', "修改成功!");
        }else{
            echoAjaxJson('-1', "修改失败，请刷新页面重试!");
        }
    }

    //添加明细
    public function createReimburseMain(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }
        $input = Input::all();

        $rules = [
            'exp_remark' => 'required|between:1,150',
            'exp_amount' => 'required|numeric|min:0.01',
        ];
        $message = [
            'exp_remark.required' => '请填写用途',
            'exp_remark.between' => '用途字符数超出范围',
            'exp_amount.required' => '请填写金额',
            'exp_amount.numeric' => '请输入数字',
            'exp_amount.min' => '金额不能小于或者等于0',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }

        //获取单据信息
        $expense = ExpenseDb::where('expense_user', session('userInfo.user_id'))
            ->where('expense_status', '202')
            ->where('expense_type', 'reimburse')
            ->get()
            ->first();
        if(!$expense){
            echoAjaxJson('-1', '保存失败，单据信息获取失败，请刷新后重试！');
        }
        //创建数据
        $data['expense_id'] = $expense['expense_id'];
        $data['exp_remark'] = $input['exp_remark'];
        $data['exp_amount'] = $input['exp_amount'];
        $data['exp_user'] = session('userInfo.user_id');
        $data['enclosure'] = $input['enclosure'] ? 1 : 0;
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['updated_at'] = date('Y-m-d H:i:s', time());

        //事物创建数据
        $result = DB::transaction(function () use($data, $input) {
            //创建明细
            $expId = ExpenseMainDb::insertGetId($data);
            //创建附件
            if($input['enclosure']){
                $num = explode(',', $input['enclosure']);
                foreach($num as $v){
                    $dataEnclo['expense_id'] = $data['expense_id'];
                    $dataEnclo['exp_id'] = $expId;
                    $dataEnclo['enclo_type'] = 'reimburse';
                    $dataEnclo['enclo_user'] = session('userInfo.user_id');
                    $dataEnclo['enclo_url'] = 'uploads/reimburse/'.$data['expense_id'].'/'.$v;
                    $dataEnclo['created_at'] = date('Y-m-d H:i:s', time());
                    $dataEnclo['updated_at'] = date('Y-m-d H:i:s', time());
                    ExpEnclosureDb::insert($dataEnclo);
                }
            }
            return true;
        });

        if($result){
            echoAjaxJson('1', "添加成功!");
        }else{
            echoAjaxJson('-1', "添加失败，请刷新页面重试!");
        }
    }

    //删除凭证
    public function delReimburse(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }
        $input = Input::all();

        //过滤信息
        $rules = [
            'id' => 'required|integer|digits_between:1,11',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.integer' => '参数类型错误',
            'id.digits_between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }
        $id = $input['id'];

        //获取单据信息
        $expense = ExpenseDb::where('expense_id', $id)
            ->where('expense_status', '202')
            ->where('expense_type', 'reimburse')
            ->get()
            ->first();
        if (!$expense) {
            echoAjaxJson('-1', "删除失败，单据信息获取失败");
        }

        //事物删除数据
        $result = DB::transaction(function () use($id) {
            ExpenseDb::where('expense_id', $id)
                ->delete();
            ExpenseMainDb::where('expense_id', $id)
                ->delete();
            ExpEnclosureDb::where('expense_id', $id)
                ->delete();
            return true;
        });

        //删除上传的图片
        $directory = 'reimburse/'.$id;
        Storage::disk('storageTemp')->deleteDirectory($directory);
        if($result){
            echoAjaxJson('1', "删除成功");
        }else{
            echoAjaxJson('-1', "删除失败，请刷新后重试");
        }
    }

    //上传图片
    public function uploadImg(Request $request){
        //获取单据信息
        $result = ExpenseDb::where('expense_user', session('userInfo.user_id'))
            ->where('expense_status', '202')
            ->where('expense_type', 'reimburse')
            ->get()
            ->first();
        if(!$result){
            echoAjaxJson('-1', '上传失败，单据信息获取失败，请刷新后重试！');
        }

        $file = $request->file('file');
        $ext_mime = ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"];//扩展名

        if (!$file->isValid()) {
            echoAjaxJson('-1', '上传失败，请刷新后重试！');
        }
        // 获取文件相关信息
        //$originalName = $file->getClientOriginalName();               // 文件原名
        $ext = $file->getClientOriginalExtension();                     // 扩展名
        $realPath = $file->getRealPath();                               //临时文件的绝对路径
        $type = $file->getClientMimeType();                             //mime
        $size = $file->getClientSize()/1000/1000;                       //获取文件尺寸MB
        $fileName = date('Y-m-d-H-i-s'). '-' . uniqid() . '.' .$ext;    // 上传文件名
        if(!in_array($type, $ext_mime)){
            echoAjaxJson('-1', '上传失败，只能上传图片（jpg、jpeg、png、gif、bmp）！');
        }
        if($size > 5){
            return echoAjaxJson('-1', '上传失败，图片过大，最大支持5MB！');
        }
        $filePath = 'uploads/reimburse/'.$result['expense_id'];
        $rel = $file->move($filePath ,$fileName);

        //$rel = Storage::disk('storageTemp')->put($filePath, file_get_contents($realPath));
        if(!$rel){
            echoAjaxJson('-1', '上传失败，请刷新后重试！');
        }
        $url = asset('uploads/reimburse/'.$result['expense_id']).'/'.$fileName;
        $data = $fileName;

        echoAjaxJson('1', $url, $data);
    }
}
