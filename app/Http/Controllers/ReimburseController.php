<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Expense\ExpenseModel AS ExpenseDb;
use App\Http\Models\Expense\ExpenseMainModel AS ExpenseMainDb;
use App\Http\Models\Expense\ExpEnclosureModel AS ExpEnclosureDb;
use App\Http\Models\AuditProcess\AuditProcessModel AS AuditProcessDb;
use App\Http\Models\AuditProcess\AuditInfoModel AS AuditInfoDb;
use App\Http\Models\User\UserModel AS UserDb;
use App\Http\Models\Subjects\SubjectsModel AS SubjectsDb;
use App\Http\Models\Budget\BudgetSubjectDateModel AS BudgetSubjectDateDb;
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
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

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
                'exp.expense_title AS exp_title', 'exp.expense_date AS exp_date', 'exp.expense_status AS exp_status', 'exp.created_at AS add_time')
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
            $expenseDb['expense_id'] = getId();
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
        $data['expMain'] = ExpenseMainDb::from('expense_main AS expM')
            ->leftjoin('expense_enclosure AS expE', 'expM.exp_id', '=', 'expE.exp_id')
            ->leftjoin('subjects AS sub', 'expM.subject_id_debit', '=', 'sub.sub_id')
            ->where('expM.expense_id', $data['expense_id'])
            ->select('expM.exp_id', 'expM.exp_remark', 'expM.exp_amount', 'expM.enclosure', 'expE.enclo_url AS url',
                'sub.sub_name AS exp_debit', 'sub.sub_pid AS exp_debit_pid')
            ->orderBy('expM.created_at', 'asc')
            ->get()
            ->toArray();
        return view('reimburse.addReimburse', $data);
    }

    //编辑报销单据视图
    public function editReimburse()
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('reimburse.index'));
        }
        $id = $input['id'];

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
        $data['expMain'] = ExpenseMainDb::from('expense_main AS expM')
            ->leftjoin('expense_enclosure AS expE', 'expM.exp_id', '=', 'expE.exp_id')
            ->leftjoin('subjects AS sub', 'expM.subject_id_debit', '=', 'sub.sub_id')
            ->where('expM.expense_id', $data['expense_id'])
            ->select('expM.exp_id', 'expM.exp_remark', 'expM.exp_amount', 'expM.enclosure', 'expE.enclo_url AS url',
                'sub.sub_name AS exp_debit', 'sub.sub_pid AS exp_debit_pid')
            ->orderBy('expM.created_at', 'asc')
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
            'exp_id' => 'required|between:32,32',
            'exp_date' => 'required|date',
        ];
        $message = [
            'exp_id.required' => '缺少必要参数',
            'exp_id.between' => '参数错误',
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
            'sub_debit' => 'required|between:32,32',
        ];
        $message = [
            'exp_remark.required' => '请填写用途',
            'exp_remark.between' => '用途字符数超出范围',
            'exp_amount.required' => '请填写金额',
            'exp_amount.numeric' => '请输入数字',
            'exp_amount.min' => '金额不能小于或者等于0',
            'sub_debit.required' => '请选择科目借',
            'sub_debit.between' => '科目借参数错误',
        ];
        if(session('userInfo.sysConfig.reimburse.budgetOnOff') == 1){
            $rules['budget_id'] = 'required|between:32,32';
            $message['budget_id.required'] = '请选择预算';
            $message['budget_id.between'] = '预算参数错误';
        }
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

        $date = date('Y-m-d', time());
        if(session('userInfo.sysConfig.reimburse.budgetOnOff') == 1) {
            //获取科目金额
            $budgetAmount = BudgetSubjectDateDb::where('budget_id', $input['budget_id'])
                ->where('subject_id', $input['sub_debit'])
                ->where('budget_date', '<=', $date)
                ->sum('budget_amount');
            //获取除拒绝外的报销费用
            $reimburse = ExpenseMainDb::from('expense_main AS expM')
                ->leftjoin('expense AS exp', 'exp.expense_id', '=', 'expM.expense_id')
                ->where('expM.budget_id', $input['budget_id'])
                ->whereNotIn('exp.expense_status', ['200', '1003'])
                ->sum('expM.exp_amount');

            $result = $budgetAmount - $reimburse - $input['exp_amount'];
            if ($result < 0) {
                echoAjaxJson('-1', '预算科目金额不足，无法提交！');
            }
        }

        //移动单据文件
        if($input['enclosure']){
            $directory = 'reimburse/'.$expense['expense_id'].'/'.$input['enclosure'];
            $exists = Storage::disk('storageTemp')->exists($directory);
            if(!$exists){
                echoAjaxJson('-1', '保存失败，附件获取失败，请刷新后重试！');
            }
            $oldFile = 'uploads/reimburse/'.$expense['expense_id'].'/'.$input['enclosure'];
            $newFile = 'enclosure/reimburse/'.$expense['expense_id'].'/'.$input['enclosure'];
            $result = Storage::move($oldFile, $newFile);
            if(!$result){
                echoAjaxJson('-1', '保存失败，附件保存失败，请刷新后重试！');
            }
        }

        //创建数据
        $data['exp_id'] = getId();
        $data['expense_id'] = $expense['expense_id'];
        $data['budget_id'] = $input['budget_id'];
        $data['subject_id_debit'] = $input['sub_debit'];
        $data['exp_remark'] = $input['exp_remark'];
        $data['exp_amount'] = $input['exp_amount'];
        $data['exp_user'] = session('userInfo.user_id');
        $data['enclosure'] = $input['enclosure'] ? 1 : 0;
        $data['created_at'] = date('Y-m-d H:i:s', time());
        $data['updated_at'] = date('Y-m-d H:i:s', time());

        //事物创建数据
        $result = DB::transaction(function () use($data, $input) {
            //创建明细
            ExpenseMainDb::insert($data);
            //创建附件
            if($input['enclosure']){
                $num = explode(',', $input['enclosure']);
                foreach($num as $v){
                    $dataEnclo['enclo_id'] = getId();
                    $dataEnclo['expense_id'] = $data['expense_id'];
                    $dataEnclo['exp_id'] = $data['exp_id'];
                    $dataEnclo['enclo_type'] = 'reimburse';
                    $dataEnclo['enclo_user'] = session('userInfo.user_id');
                    $dataEnclo['enclo_url'] = 'reimburse/'.$data['expense_id'].'/'.$v;
                    $dataEnclo['created_at'] = date('Y-m-d H:i:s', time());
                    $dataEnclo['updated_at'] = date('Y-m-d H:i:s', time());
                    ExpEnclosureDb::insert($dataEnclo);
                }
            }

            $rel['result'] = true;
            $rel['expId'] = $data['exp_id'];
            $rel['url'] = $input['enclosure'] ? asset('enclosure/'.$dataEnclo['enclo_url']) : '';
            return $rel;
        });


        if($result['result']){
            echoAjaxJson('1', "添加成功!", array('id'=>$result['expId'], 'url'=>$result['url']));
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

        //获取单据信息
        $expense = ExpenseDb::where('expense_id', $id)
            ->where('expense_status', '202')
            ->where('expense_type', 'reimburse')
            ->get()
            ->first();
        if (!$expense) echoAjaxJson('-1', "删除失败，单据信息获取失败");
        if ($expense['expense_status'] != '202') echoAjaxJson('-1', "删除失败，单据状态不正确");

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
        Storage::disk('storage')->deleteDirectory($directory);
        if($result){
            echoAjaxJson('1', "删除成功");
        }else{
            echoAjaxJson('-1', "删除失败，请刷新后重试");
        }
    }

    //删除凭证明细
    public function delReimburseMain(Request $request)
    {
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

        //获取单据明细信息
        $expMain = ExpenseMainDb::where('exp_id', $id)
            ->get()
            ->first();
        if (!$expMain) echoAjaxJson('-1', "删除失败，单据信息获取失败");

        //获取单据信息
        $expense = ExpenseDb::where('expense_id', $expMain['expense_id'])
            ->where('expense_status', '202')
            ->where('expense_type', 'reimburse')
            ->get()
            ->first();
        if (!$expense) echoAjaxJson('-1', "删除失败，单据信息获取失败");
        if ($expense['expense_status'] != '202') echoAjaxJson('-1', "删除失败，单据状态不正确");

        $expEnclo = '';
        if($expMain['enclosure']){
            //获取附件信息
            $expEnclo = ExpEnclosureDb::where('exp_id', $id)
                ->get()
                ->first();
        }

        //事物删除数据
        $result = DB::transaction(function () use($id, $expEnclo) {
            //删除明细
            ExpenseMainDb::where('exp_id', $id)
                ->delete();
            //删除附件
            if($expEnclo){
                ExpEnclosureDb::where('exp_id', $id)
                    ->delete();
            }
            return true;
        });

        if($expEnclo) {
            //删除上传的图片
            $directory = mb_substr($expEnclo['enclo_url'], 8, mb_strlen($expEnclo['enclo_url']));
            Storage::disk('storage')->delete($directory);
        }
        if($result){
            echoAjaxJson('1', "删除成功");
        }else{
            echoAjaxJson('-1', "删除失败，请刷新后重试");
        }
    }

    //查看单据详情
    public function listReimburse()
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('reimburse.index'));
        }
        $id = $input['id'];

        //获取单据
        $data = ExpenseDb::from('expense AS exp')
            ->leftJoin('department AS dep', 'dep.dep_id','=','exp.expense_dep')
            ->leftJoin('users AS u', 'u.user_id','=','exp.expense_user')
            ->select('u.user_name AS user_name', 'dep.dep_name AS dep_name', 'exp.expense_num', 'exp.expense_id',
                'exp.expense_title', 'exp.expense_date', 'exp.expense_status')
            ->where('exp.expense_type', 'reimburse')
            ->get()
            ->first();
        if(!$data){
            return redirectPageMsg('-1', '单据信息获取失败，请刷新后重试', route('reimburse.index'));
        }
        //获取明细
        $data['expMain'] = ExpenseMainDb::from('expense_main AS expM')
            ->leftjoin('expense_enclosure AS expE', 'expM.exp_id', '=', 'expE.exp_id')
            ->leftjoin('subjects AS sub', 'expM.subject_id_debit', '=', 'sub.sub_id')
            ->where('expM.expense_id', $id)
            ->select('expM.exp_id', 'expM.exp_remark', 'expM.exp_amount', 'expM.enclosure', 'expE.enclo_url AS url',
                'sub.sub_name AS exp_debit', 'sub.sub_pid AS exp_debit_pid')
            ->orderBy('expM.created_at', 'asc')
            ->get()
            ->toArray();

        //获取审批进度
        $audit = AuditInfoDb::from('audit_info AS ai')
            ->leftjoin('audit_info_text AS ait', 'ai.process_id', '=', 'ait.process_id')
            ->leftjoin('users AS u', 'u.user_id', '=', 'ait.created_user')
            ->leftjoin('users_base AS ub', 'ub.user_id', '=', 'ait.created_user')
            ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->where('ai.process_type', 'reimburse')
            ->where('ai.process_app', $id)
            ->select('ait.audit_res','u.user_name', 'pos.pos_name')
            ->orderby('ait.audit_sort', 'asc')
            ->get()
            ->toArray();
        $data['audit'] = json_encode($audit);
    
        return view('reimburse.listReimburse', $data);
    }

    //确认收款
    public function confirmPay(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }
        //获取post数据
        $input = Input::all();

        //过滤信息
        $rules = [
            'id' => 'required|between:32,32'
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }

        //单据号是否存在
        $exp = ExpenseDb::where('expense_id', $input['id'])
            ->where('expense_status', '204')
            ->where('expense_user', session('userInfo.user_id'))
            ->select('expense_id', 'expense_user', 'expense_num')
            ->get()
            ->first();
        if(!$exp){
            echoAjaxJson('-1', '单据获取失败或未到确认付款步骤，请重试');
        }

        //更新状态
        $result = ExpenseDb::where('expense_id', $input['id'])
            ->where('expense_status', '204')
            ->where('expense_user', session('userInfo.user_id'))
            ->select('expense_id', 'expense_user', 'expense_num')
            ->update(array('expense_status'=>'201'));
        if($result){
            echoAjaxJson('1', '操作成功');
        }else{
            echoAjaxJson('-1', '操作失败，请刷新重试');
        }
    }
    
    //提交审批
    public function addAudit(Request $request)
    {
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

        $reimburse = ExpenseDb::where('expense_id', $id)
            ->where('expense_type', 'reimburse')
            ->get()
            ->first();
        if(!$reimburse) echoAjaxJson('-1', '参数错误，单据不存！');
        if(!$reimburse['reimburse'] != '202') echoAjaxJson('-1', '提交失败，单据状态错误！');

        if(session('userInfo.sysConfig.reimburse.budgetOnOff') == 1) {
            //查看明细参数是否完整
            $isNull = ExpenseMainDb::where('expense_id', $id)
                ->where(function ($query) {
                    $query->orWhere('budget_id', '')
                        ->orWhere('subject_id_debit', '');
                })
                ->get()
                ->first();
            if ($isNull) echoAjaxJson('-1', '单据明细“' . $isNull['exp_remark'] . '”中预算或者科目未选择，请编辑后再提交！');
        }

        //获取预算审批流程
        $whereIn[] = 0;
        $whereIn[] = session('userInfo.dep_id');
        $auditArr = AuditProcessDb::where('audit_type', 'reimburse')
            ->whereIn('audit_dep', $whereIn)
            ->get()
            ->toArray();
   
        //删除临时上传的图片
        $directory = 'reimburse/'.$id;
        Storage::disk('storageTemp')->deleteDirectory($directory);

        if($auditArr){
            //审批流程大于1则获取对应部门预算
            if(count($auditArr) > 1){
                foreach($auditArr as $k => $v){
                    if($v['audit_dep'] == session('userInfo.dep_id')){
                        $auditArr = $v;
                    }
                }
            }else{
                $auditArr = $auditArr[0];
            }

            if(!$auditArr['audit_process']) echoAjaxJson('-1', '提交失败，审批流程人员获取失败！');

            $result = DB::transaction(function () use($id, $reimburse, $input, $auditArr) {
                $process_users = explode(',', $auditArr['audit_process']);
                //审批内容参数
                $auditInfoDb = new AuditInfoDb();
                $auditInfoDb->process_id = getId();
                $auditInfoDb->process_type = 'reimburse';
                $auditInfoDb->process_app = $reimburse['expense_id'];
                $auditInfoDb->process_title = '单据号：'.$reimburse['expense_num'];
                $auditInfoDb->process_text = $input['process_text'];
                $auditInfoDb->process_users = $auditArr['audit_process'];
                $auditInfoDb->process_audit_user = $process_users[0];
                $auditInfoDb->created_user = session('userInfo.user_id');
                $auditInfoDb->status = '1000';
                $auditInfoDb->save();

                //更新报销单据状态
                ExpenseDb::where('expense_id', $id)
                    ->where('expense_type', 'reimburse')
                    ->update(array('expense_status'=>1009));
                return true;
            });

            if($result){
                echoAjaxJson('1', '提交成功，请耐心等待审批！');
            }else{
                echoAjaxJson('-1', '审批失败，请重新提交！');
            }
        }else{
            $result = DB::transaction(function () use($id) {
                //转换状态
                $data['expense_status'] = '203';
                $data['expense_cashier'] = session('userInfo.sysConfig.reimburse.userCashier');
                //更新单据状态
                ExpenseDb::where('expense_id', $id)
                    ->where('expense_type', 'reimburse')
                    ->where('expense_status', '202')
                    ->update($data);
                //获取单据状态
                $expense = ExpenseDb::where('expense_id', $id)
                    ->where('expense_type', 'reimburse')
                    ->select('expense_num')
                    ->get()
                    ->first();

                //发送出纳通知
                $notice['notice_id'] = getId();
                $notice['notice_app'] = $id;//需要确认操作
                $notice['notice_message'] = '报销单据：编号'.$expense['expense_num'].'。已通过审批，等待付款。';
                $notice['notice_user'] = session('userInfo.sysConfig.reimburse.userCashier');
                $this->createNotice($notice);
                return true;
            });

            if($result){
                echoAjaxJson('1', '审批通过，因未匹配到相应审批流程，预算将直接通过审批！');
            }else{
                echoAjaxJson('-1', '审批失败，请重新提交！');
            }
        }
    }

    //查看审批进度
    public function listAudit(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
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
        //获取编辑状态单据
        $expense = ExpenseDb::where('expense_id', $id)
            ->where('expense_type', 'reimburse')
            ->get()
            ->first();
        if(!$expense){
            return redirectPageMsg('-1', '单据信息获取失败，请刷新后重试', route('reimburse.index'));
        }

        //获取审批流程信息
        $audit = AuditInfoDb::where('process_type', 'reimburse')
            ->where('process_app', $id)
            ->select('process_audit_user', 'process_users', 'process_id', 'status')
            ->get()
            ->first();
        if(!$audit){
            echoAjaxJson('-1', '该项目未提交审批!');
        }

        //格式化流程
        $data['audit_user'] = $audit['process_audit_user'];
        $auditUser = explode(',', $audit['process_users']);

        $result = UserDb::leftjoin('users_base AS ub', 'users.user_id', '=', 'ub.user_id')
            ->leftjoin('department AS dep', 'ub.department', '=', 'dep.dep_id')
            ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->leftjoin('audit_info_text AS aif', 'users.user_id', '=', 'aif.created_user')
            ->select('dep.dep_name', 'pos.pos_name', 'users.user_name', 'users.user_id AS uid',
                'aif.audit_text', 'aif.audit_res', 'aif.created_at AS audit_time')
            ->whereIn('users.user_id', $auditUser)
            ->where('aif.process_id', $audit['process_id'])
            ->get()
            ->toArray();

        //格式化数据
        $data['auditProcess'] = array();
        $data['audit_status'] = $audit['status'];
        $data['status'] = 1;
        foreach($auditUser as $k => $v){
            foreach($result as $u => $d){
                if($v == $d['uid']) $data['auditProcess'][] = $d;
            }
        }
 
        //返回结果
        ajaxJsonRes($data);
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
        $data['fUrl'] = $url;
        $data['url'] = $fileName;
        echoAjaxJson('1', '上传成功', $data);
    }

    //获取预算科目
    public function getBudgetSub(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //开启报销预算
        if(session('userInfo.sysConfig.reimburse.budgetOnOff') == 1){
            //获取参数
            $input = Input::all();
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

            $subjects = SubjectsDb::leftjoin('budget_subject AS bs', function ($join) use ($input) {
                $join->on('bs.subject_id', '=', 'subjects.sub_id')
                    ->where('bs.budget_id', $input['id']);
            })
                ->where('subjects.status', 1)
                ->select('subjects.sub_id AS id', 'subjects.sub_ip AS sub_ip', 'subjects.sub_pid AS pid',
                    'subjects.sub_name AS text', 'bs.status AS status')
                ->orderBy('subjects.sub_ip', 'ASC')
                ->get()
                ->toArray();
        }else{
            $subjects = SubjectsDb::where('status', 1)
                ->select('sub_id AS id', 'sub_ip', 'sub_pid AS pid', 'sub_name AS text', 'status')
                ->orderBy('sub_ip', 'ASC')
                ->get()
                ->toArray();
        }

        //树形排列科目
        $result = getTree($subjects, session('userInfo.sysConfig.reimburse.subReimburse'), 1);

        //创建结果数据
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //核销预算金额
    public function getCheckAmount(Request $request)
    {
        $result = 0;
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }
        //获取参数
        $input = Input::all();
        $rules = [
            'sub_id' => 'required|between:32,32',
            'sub_pid' => 'required|between:32,32',
        ];
        $message = [
            'sub_id.required' => '参数不存在',
            'sub_id.between' => '参数错误',
            'sub_pid.required' => '父级科目参数不存在',
            'sub_pid.between' => '父级科目参数错误',
        ];
        if(session('userInfo.sysConfig.reimburse.budgetOnOff') == 1){
            $rules['budget_id'] = 'required|between:32,32';
            $message['budget_id.required'] = '参数不存在';
            $message['budget_id.between'] = '参数错误';
        }
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            echoAjaxJson('-1', $validator->errors()->first());
        }
        //开启报销预算
        if(session('userInfo.sysConfig.reimburse.budgetOnOff') == 1) {
            $date = date('Y-m-d', time());

            //获取科目金额
            $budgetAmount = BudgetSubjectDateDb::where('budget_id', $input['budget_id'])
                ->where('subject_id', $input['sub_id'])
                ->where('budget_date', '<=', $date)
                ->sum('budget_amount');
            //获取除拒绝报销费用
            $reimburse = ExpenseMainDb::from('expense_main AS expM')
                ->leftjoin('expense AS exp', 'exp.expense_id', '=', 'expM.expense_id')
                ->where('expM.budget_id', $input['budget_id'])
                ->whereNotIn('exp.expense_status',['200','1003'])
                ->sum('expM.exp_amount');
            $result = $budgetAmount - $reimburse;
        }

        //创建结果数据
        $data['parSub'] = mapKey(session('userInfo.subject'), $input['sub_pid'], 1);
        $data['data'] = $result;
        $data['status'] = 1;
        
        //返回结果
        ajaxJsonRes($data);
    }
}
