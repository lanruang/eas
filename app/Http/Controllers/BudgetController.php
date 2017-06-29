<?php

namespace App\Http\Controllers;

use App\Http\Models\Budget\BudgetModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\Budget\BudgetModel AS BudgetDb;
use App\Http\Models\Budget\BudgetSubjectModel AS BudgetSubjectDb;
use App\Http\Models\Budget\BudgetSubjectDateModel AS BudgetSubjectDateDb;
use App\Http\Models\Budget\BudgetDepartmentModel AS BudgetDepartmentDb;
use App\Http\Models\Subjects\SubjectsModel AS SubjectsDb;
use App\Http\Models\AuditProcess\AuditProcessModel AS auditProcessDb;
use App\Http\Models\AuditProcess\AuditInfoModel AS auditInfoDb;
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
            return redirectPageMsg('-1', "无法添加预算，已存在编辑状态预算", route('budget.index'));
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
        $data['budget_num'] = $input['budget_num'];
         $data['budget_name'] = $input['budget_name'];
         $data['budget_start'] = $input['budget_start'];
         $data['budget_end'] = $input['budget_end'];
         $data['status'] = $input['status'];
         $data['create_user'] = session('userInfo.user_id');
        $result = BudgetModel::insertGetId($data);

        if($result){
            return redirectPageMsg('1', "添加成功，将添加预算项", route('budget.addBudgetSub')."/".$result);
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

    //添加预算项
    public function createBudgetSub()
    {
        //验证表单
        $input = Input::all();
        if(!array_key_exists('budget_id', $input)){
            return redirectPageMsg('-1', '参数错误', route('budget.index'));
        };
        $rules = [
            'budget_id' => 'required|digits_between:0,11|numeric',
            'subject_id' => 'required|digits_between:0,11|numeric',
        ];
        $message = [
            'budget_id.required' => '参数不存在',
            'budget_id.between' => '参数错误',
            'budget_id.numeric' => '参数错误',
            'subject_id.required' => '科目参数不存在',
            'subject_id.between' => '科目参数错误',
            'subject_id.numeric' => '科目参数错误',
        ];

        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('budget.addBudgetSub')."/".$input['budget_id']);
        }

        //获取预算信息
        $budget = BudgetModel::where('budget_id', $input['budget_id'])
                            ->get()
                            ->first();
        $monNum = getMonToMonNum($budget['budget_start'], $budget['budget_end']);
        if(!$budget){
            return redirectPageMsg('-1', "参数错误，预算不存在", route('budget.addBudgetSub')."/".$input['budget_id']);
        }
        $NowDate = date("Y-m",strtotime($budget['budget_start']));
        //获取预算项目
        $budgetS = BudgetSubjectDb::where('budget_id',$input['budget_id'])
                                ->where('subject_id', $input['subject_id'])
                                ->get()
                                ->first();

        //是否在更新状态
        $is_budgetSUp = false;
        if($budgetS){
            $is_budgetSUp = $budgetS['status'] == '102' ? true : false;
        }
        //获取预算项目金额
        $budgetSD = BudgetSubjectDateDb::where('budget_id',$input['budget_id'])
                                    ->where('subject_id', $input['subject_id'])
                                    ->get()
                                    ->toArray();
        //预算项目更新数据
        $sData['sum_amount'] = 0;
        //格式化数据
        for($i=0; $i <= $monNum; $i++){
            if(!array_key_exists('date_'.$NowDate, $input)) {
                return redirectPageMsg('-1', '期间数不符，请刷新后重试', route('budget.addBudgetSub')."/".$input['budget_id']);
            }
            $budget_amount_raw = $input['date_'.$NowDate];
            $created_at = date("Y-m-d H:i:s", time());
            $updated_at = date("Y-m-d H:i:s", time());
            //复制原始预算金额
            if($is_budgetSUp == false){
                foreach($budgetSD as $k => $v){
                    if($v['budget_date'] == $NowDate){
                        $budget_amount_raw = $v['budget_amount_raw'];
                        $created_at = $v['created_at'];
                    }
                }
            }
            $data[$i]['budget_id'] = $input['budget_id'];
            $data[$i]['subject_id'] = $input['subject_id'];
            $data[$i]['budget_date'] = $NowDate;
            $data[$i]['budget_amount'] = $input['date_'.$NowDate];
            $data[$i]['budget_amount_raw'] = $budget_amount_raw;
            $data[$i]['status'] = 102;
            $data[$i]['created_at'] = $created_at;
            $data[$i]['updated_at'] = $updated_at;
            $sData['sum_amount'] = $sData['sum_amount']+$input['date_'.$NowDate];
            $NowDate = date("Y-m",strtotime("$NowDate +1 month"));
        }
        $sData['budget_id'] = $input['budget_id'];
        $sData['subject_id'] = $input['subject_id'];
        $sData['sum_amount'] = round($sData['sum_amount'], 2);
        $sData['status'] = 102;

        //事务
        $budget_id = $input['budget_id'];
        $subject_id = $input['subject_id'];
        $result = DB::transaction(function () use($data, $sData, $budget_id, $subject_id, $budgetS) {
            //更新/创建预算项目
            if($budgetS){
                $sData['updated_at'] = date("Y-m-d H:i:s", time());
                BudgetSubjectDb::where('budget_id', $budget_id)
                            ->where('subject_id', $subject_id)
                            ->update($sData);
            }else{
                $sData['created_at'] = date("Y-m-d H:i:s", time());
                $sData['updated_at'] = date("Y-m-d H:i:s", time());
                BudgetSubjectDb::insert($sData);
            }
            //创建预算项目期间
                //删除原数据
                BudgetSubjectDateDb::where('budget_id', $budget_id)
                                    ->where('subject_id', $subject_id)
                                    ->delete();
                //创建新数据
                BudgetSubjectDateDb::insert($data);
            return true;
        });

        if($result){
            return redirectPageMsg('1', '添加成功', route('budget.addBudgetSub')."/".$input['budget_id']);
        }else{
            return redirectPageMsg('-1', '添加失败', route('budget.addBudgetSub')."/".$input['budget_id']);
        }
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

        $subjects = SubjectsDb::leftjoin('budget_subject AS bs', function ($join) use($input) {
                $join->on('bs.subject_id','=','subjects.sub_id')
                    ->where('bs.budget_id', $input['budget_id']);})
            ->where('subjects.status', 1)
            ->select('subjects.sub_pid AS pid', 'subjects.sub_id AS id', 'subjects.sub_name AS subject',
                'subjects.sub_ip AS subject_ip', 'subjects.sub_budget', 'bs.sum_amount AS budget_amount',
                'bs.status AS status')
            ->orderBy('subjects.sub_ip', 'ASC')
            ->get()
            ->toArray();

        //树形排列科目
        $result = sortTreeBudget($subjects, 0, 0, 1);

        //倒叙科目汇总金额
        $result = array_reverse($result);
        foreach($result as $k => $v){
            $result[$k]['parent'] = ($v['pid'] == 0) ? 1 : 0;
            foreach($result as $kk => $vv){
                if($v['id'] == $vv['pid'] && $v['pid'] != 0){
                    $result[$k]['budget_amount'] = sprintf("%.2f", $result[$k]['budget_amount'] + $vv['budget_amount']);
                    $result[$k]['parent'] = 1;
                }
            }
        }
        $result = array_reverse($result);

        //创建结果数据
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }
    
    //获取预算期间
    public function getBudgetDate(Request $request)
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
            'subject_id' => 'required|digits_between:1,11',
        ];
        $message = [
            'budget_id.required' => '参数不存在',
            'budget_id.digits_between' => '参数错误',
            'subject_id.required' => '科目参数不存在',
            'subject_id.digits_between' => '科目参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }

        $result = BudgetSubjectDateDb::where('budget_id', $input['budget_id'])
            ->where('subject_id', $input['subject_id'])
            ->select('budget_date', 'budget_amount')
            ->orderBy('budget_date','ASC')
            ->get()
            ->toArray();

        //创建结果数据
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //查看预算详情
    public function listBudget($id = '0')
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

        return view('budget.listBudget', $budget);
    }

    //删除预算
    public function delBudget(Request $request)
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
  
        $budget = BudgetModel::where('budget_id',$id)
                            ->get()
                            ->first();

        if(!$budget){
            echoAjaxJson('-1', '参数错误，预算不存在！');
        }
        if($budget['status'] != "102"){
            echoAjaxJson('-1', '删除失败，该预算无法删除！');
        }

        $result = DB::transaction(function () use($id) {
            //删除预算
            BudgetModel::where('budget_id', $id)
                        ->delete();
            //删除预算项目
            BudgetSubjectDb::where('budget_id', $id)
                ->delete();
            //删除预算项目金额
            BudgetSubjectDateDb::where('budget_id', $id)
                ->delete();
            return true;
        });

        if($result){
            echoAjaxJson('1', '删除成功');
        }else{
            echoAjaxJson('-1', '删除失败');
        }
    }
    
    //提交预算
    public function subBudget(Request $request)
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
        //获取预算信息
        $budget = BudgetDb::where('budget_id',$id)
            ->get()
            ->first();
        //获取预算项目
        $budgetS = BudgetSubjectDb::where('budget_id',$id)
                            ->where('status','102')
                            ->get()
                            ->first();
        //验证预算
        if(!$budget) echoAjaxJson('-1', '参数错误，预算不存在！');
        if($budget['status'] != "102" && !$budgetS) echoAjaxJson('-1', '提交失败，该预算无审核内容！');

        $whereIn[] = 0;
        $whereIn[] = session('userInfo.dep_id');
        //获取预算审核流程
        $budgetAudit = auditProcessDb::where('audit_type', 'budget')
                                    ->whereIn('audit_dep', $whereIn)
                                    ->get()
                                    ->toArray();
        if($budgetAudit){
            //审核流程大于1则获取对应部门预算
            if(count($budgetAudit) > 1){
                foreach($budgetAudit as $k => $v){
                    if($v['audit_dep'] == session('userInfo.dep_id')){
                        $budgetAudit = $v;
                    }
                }
            }else{
                $budgetAudit = $budgetAudit[0];
            }

            if(!$budgetAudit['audit_process']) echoAjaxJson('-1', '提交失败，审核流程人员获取失败！');

            $result = DB::transaction(function () use($id, $budget, $input, $budgetAudit) {
                $process_users = explode(',', $budgetAudit['audit_process']);
                //审核内容参数
                $data = array();
                $auditInfoDb = new auditInfoDb();
                $auditInfoDb->process_type = 'budget';
                $auditInfoDb->process_app = $budget['budget_id'];
                $auditInfoDb->process_title = '编号('.$budget['budget_num'].')—'.$budget['budget_name'];
                $auditInfoDb->process_text = $input['process_text'];
                $auditInfoDb->process_users = $budgetAudit['audit_process'];
                $auditInfoDb->process_user_next = $process_users[0];
                $auditInfoDb->status = '1000';
                $auditInfoDb->save();

                //更新预算
                BudgetModel::where('budget_id', $id)
                    ->update(array('status'=>9));
                //更新预算项目
                BudgetSubjectDb::where('budget_id', $id)
                    ->where('status','102')
                    ->update(array('status'=>9));
                //更新预算项目金额
                BudgetSubjectDateDb::where('budget_id', $id)
                    ->where('status','102')
                    ->update(array('status'=>9));
                return true;
            });

            if($result){
                echoAjaxJson('1', '提交成功，请耐心等待审核！');
            }else{
                echoAjaxJson('-1', '审核失败，请重新提交！');
            }
        }else{
            $result = DB::transaction(function () use($id) {
                //更新预算
                BudgetModel::where('budget_id', $id)
                            ->update(array('status'=>1));
                //更新预算项目
                BudgetSubjectDb::where('budget_id', $id)
                            ->where('status','102')
                            ->update(array('status'=>1));
                //更新预算项目金额
                BudgetSubjectDateDb::where('budget_id', $id)
                            ->where('status','102')
                            ->update(array('status'=>1));
                return true;
            });

            if($result){
                echoAjaxJson('1', '审核通过，因未匹配相应审核流程，预算将直接通过审核！');
            }else{
                echoAjaxJson('-1', '审核失败，请重新提交！');
            }
        }
    }
    
    //查看审核进度
    public function listAudit()
    {
        
    }
}
