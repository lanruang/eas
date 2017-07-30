<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Expense\ExpenseModel AS ExpenseDb;
use App\Http\Models\Department\DepartmentModel AS DepartmentDb;
use App\Http\Models\User\UserModel AS UserDb;
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
    public function getReimburse()
    {
        
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

    //上传图片
    public function uploadImg(Request $request){
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

        $rel = Storage::disk('storageTemp')->put($fileName, file_get_contents($realPath));
        if(!$rel){
            echoAjaxJson('-1', '上传失败，请刷新后重试！');
        }
        $url = asset('uploads').'/'.$fileName;

        echoAjaxJson('1', $url);
    }
}
