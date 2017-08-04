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
use App\Http\Models\User\UserModel AS UserDb;
use Illuminate\Support\Facades\DB;

class BudgetSumController extends Common\CommonController
{
    public function index()
    {
        return view('budgetSum.index');
    }

    //预算列表
    public function getBudgetSum(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //检索参数
        $searchSql = array();
        $searchSql['budget_sum'] = 1;
        if(array_key_exists('status', $input)){
            $searchSql[] = array('status', $input['status']);
        }

        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = BudgetDb::where($searchSql)->count();
        //获取数据
        $result = BudgetDb::where($searchSql)
            ->select('budget_id AS id', 'budget_num AS bd_num', 'budget_name AS bd_name',
                                'budget_start AS bd_start', 'budget_end AS bd_end' , 'status')
            ->skip($skip)
            ->take($take)
            ->orderBy('status', 'Desc')
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
    public function addBudgetSum()
    {
        //查询在编辑状态
        $result = BudgetDb::where('status', '102')
            ->first();
        if($result){
            return redirectPageMsg('-1', "无法添加预算，已存在编辑状态预算", route('budgetSum.index'));
        }

        return view('budgetSum.addBudgetSum');
    }

    //添加预算
    public function createBudgetSum()
    {
        //验证表单
        $input = Input::all();

        $rules = [
            'budget_num' => 'required|between:1,200',
            'budget_name' => 'required|between:1,200',
            'budget_date' => 'required',
            'budget_period' => 'required',
            'budget_ids' => 'required',
        ];
        $message = [
            'budget_num.required' => '请填写预算编号',
            'budget_num.between' => '预算编号字符数超出范围',
            'budget_name.required' => '请填写预算名称',
            'budget_name.between' => '预算名称字符数超出范围',
            'budget_period.required' => '请选择预算期间类型',
            'budget_date.required' => '请选择预算期间',
            'budget_ids.required' => '请选择预算',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('budgetSum.addBudgetSum'));
        }

        //检查预算编号是否存在
        $result = BudgetDb::where('budget_num', $input['budget_num'])
            ->where('budget_sum', '1')
            ->first();
        if($result){
            return redirectPageMsg('-1', "添加失败，预算编号存在", route('budgetSum.addBudgetSum'));
        }
        //获取子预算信息
        $arrIds = explode(',', $input['budget_ids']);
        $result = BudgetDb::whereIn('budget_id', $arrIds)
            ->where('budget_sum', '0')
            ->select('budget_id', 'budget_start', 'budget_end')
            ->get()
            ->toArray();

        //格式化日期数据
        $date = explode(' 一 ', $input['budget_date']);
        if(count($date) != '2'){
            return redirectPageMsg('-1', "添加失败，预算期间错误", route('budgetSum.addBudgetSum'));
        }
        if(!strtotime($date[0]) || !strtotime($date[1])){
            return redirectPageMsg('-1', "添加失败，预算期间错误", route('budgetSum.addBudgetSum'));
        }
        if(strtotime($date[0]) > strtotime($date[1])){
            return redirectPageMsg('-1', "添加失败，起始期间不能大于结束期间", route('budgetSum.addBudgetSum'));
        }
        //核实数据
        foreach($result as $k => $v){
            if(!in_array($v['budget_id'], $arrIds)){
                return redirectPageMsg('-1', "添加失败，子预算信息获取失败", route('budgetSum.addBudgetSum'));
            }
            if($v['budget_start'] > $date[1]){
                return redirectPageMsg('-1', "添加失败，子预算起始期间不能大于汇总预算结束期间", route('budgetSum.addBudgetSum'));
            }
            if($v['budget_end'] < $date[0]){
                return redirectPageMsg('-1', "添加失败，子预算结束期间不能小于汇总预算起始期间", route('budgetSum.addBudgetSum'));
            }
        }

        //预算为天数类型时时候大于31天
        if($input['budget_period'] == 'day'){
            $dateNum = (strtotime($date[1]) - strtotime($date[0]))/86400;
            if($dateNum > 30){
                return redirectPageMsg('-1', "添加失败，预算期间类型为天数时，预算期间不能大于31天", route('budgetSum.addBudgetSum'));
            }
        }

        $input['budget_start'] = $date[0];
        $input['budget_end'] = $date[1];
        $input['status'] = 102;

        //创建预算
        $data['budget_sum'] = 1;
        $data['budget_ids'] = $input['budget_ids'];
        $data['budget_num'] = $input['budget_num'];
        $data['budget_period'] = $input['budget_period'];
        $data['budget_name'] = $input['budget_name'];
        $data['budget_start'] = $input['budget_start'];
        $data['budget_end'] = $input['budget_end'];
        $data['status'] = $input['status'];
        $data['create_user'] = session('userInfo.user_id');

        $result = BudgetModel::insertGetId($data);

        if($result){
            return redirectPageMsg('1', "添加成功", route('budgetSum.index'));
        }else{
            return redirectPageMsg('-1', "添加失败", route('budgetSum.addBudgetSum'));
        }
    }

    //编辑预算视图
    public function editBudgetSum($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            return redirectPageMsg('-1', '参数错误', route('budgetSum.index'));
        };

        //获取预算信息
        $budget['budgetSum'] = BudgetDb::where('budget_id', $id)
            ->get()
            ->first();
        if(!$budget['budgetSum']){
            return redirectPageMsg('-1', "参数错误", route('budgetSum.index'));
        }
        $budget['budgetSum'] = $budget['budgetSum']->toArray();
        //获取子预算
        $budget_ids = explode(',', $budget['budgetSum']['budget_ids']);
        $budget['budget'] = BudgetDb::whereIn('budget_id', $budget_ids)
            ->select('budget_name AS name', 'budget_id AS id')
            ->get()
            ->toArray();
        if(!$budget['budget']){
            return redirectPageMsg('-1', "获取子预算失败，请重新选择", route('budgetSum.index'));
        }
        $budget['budget'] = json_encode($budget['budget']);
 
        return view('budgetSum.editBudgetSum', $budget);
    }

    //编辑预算
    public function updateBudgetSum()
    {
        //验证表单
        $input = Input::all();
        //检测id是否存在
        if(!array_key_exists('id', $input)){
            return redirectPageMsg('-1', '参数错误', route('budgetSum.index'));
        };
        $rules = [
            'budget_num' => 'required|between:1,200',
            'budget_name' => 'required|between:1,200',
            'budget_date' => 'required',
            'budget_period' => 'required',
            'budget_ids' => 'required',
        ];
        $message = [
            'budget_num.required' => '请填写预算编号',
            'budget_num.between' => '预算编号字符数超出范围',
            'budget_name.required' => '请填写预算名称',
            'budget_name.between' => '预算名称字符数超出范围',
            'budget_date.required' => '请选择预算期间',
            'budget_period.required' => '请选择预算期间类型',
            'budget_ids.required' => '请选择预算',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('budgetSum.editBudgetSum')."/".$input['id']);
        }

        //检查预算编号是否存在
        $result = BudgetDb::where('budget_num', $input['budget_num'])
                        ->where('budget_id', '<>', $input['id'])
                        ->where('budget_sum', '1')
                        ->first();
        if($result){
            return redirectPageMsg('-1', "编辑失败，预算编号存在", route('budgetSum.editBudgetSum')."/".$input['id']);
        }
        //获取子预算信息
        $arrIds = explode(',', $input['budget_ids']);
        $result = BudgetDb::whereIn('budget_id', $arrIds)
            ->where('budget_sum', '0')
            ->select('budget_id', 'budget_start', 'budget_end')
            ->get()
            ->toArray();

        //格式化日期数据
        $date = explode(' 一 ', $input['budget_date']);
        if(count($date) != '2'){
            return redirectPageMsg('-1', "编辑失败，预算期间错误", route('budgetSum.editBudgetSum')."/".$input['id']);
        }
        if(!strtotime($date[0]) || !strtotime($date[1])){
            return redirectPageMsg('-1', "编辑失败，预算期间错误", route('budgetSum.editBudgetSum')."/".$input['id']);
        }
        if(strtotime($date[0]) > strtotime($date[1])){
            return redirectPageMsg('-1', "添加失败，起始期间不能大于结束期间", route('budgetSum.editBudgetSum')."/".$input['id']);
        }
        //核实数据
        foreach($result as $k => $v){
            if(!in_array($v['budget_id'], $arrIds)){
                return redirectPageMsg('-1', "添加失败，子预算信息获取失败", route('budgetSum.editBudgetSum')."/".$input['id']);
            }
            if($v['budget_start'] > $date[1]){
                return redirectPageMsg('-1', "添加失败，子预算起始期间不能大于汇总预算结束期间", route('budgetSum.editBudgetSum')."/".$input['id']);
            }
            if($v['budget_end'] < $date[0]){
                return redirectPageMsg('-1', "添加失败，子预算结束期间不能小于汇总预算起始期间", route('budgetSum.editBudgetSum')."/".$input['id']);
            }
        }

        //预算为天数类型时时候大于31天
        if($input['budget_period'] == 'day'){
            $dateNum = (strtotime($date[1]) - strtotime($date[0]))/86400;
            if($dateNum > 30){
                return redirectPageMsg('-1', "添加失败，预算期间类型为天数时，预算期间不能大于31天", route('budgetSum.editBudgetSum')."/".$input['id']);
            }
        }

        $input['budget_start'] = $date[0];
        $input['budget_end'] = $date[1];

        //更新预算数据
        $data['budget_num'] = $input['budget_num'];
        $data['budget_name'] = $input['budget_name'];
        $data['budget_end'] = $input['budget_end'];
        $data['budget_ids'] = $input['budget_ids'];
        $data['budget_period'] = $input['budget_period'];
        $data['status'] = 102;

        $id = $input['id'];
        //更新预算
        $result = DB::transaction(function () use($id, $data) {
            //更新预算
            BudgetDb::where('budget_id', $id)
                ->where('budget_sum', '1')
                ->update($data);
            //删除预算项
            BudgetSubjectDb::where('budget_id', $id)
                ->delete();
            //删除预算项金额
            BudgetSubjectDateDb::where('budget_id', $id)
                ->delete();
            return true;
        });

        if($result){
            return redirectPageMsg('1', "编辑成功", route('budgetSum.index'));
        }else{
            return redirectPageMsg('-1', "编辑失败", route('budgetSum.editBudgetSum')."/".$input['id']);
        }
    }

    //获取预算项目
    public function getBudgetSumSub(Request $request)
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
        //获取汇总预算信息
        $budgetSum = BudgetDb::where('budget_id', $input['budget_id'])
                            ->select('budget_ids', 'budget_id', 'budget_start', 'budget_end')
                            ->get()
                            ->first();
        $ids = $budgetSum['budget_ids'];
        $ids = explode(',', $ids);

        //获取汇总预算项金额
        $subjectSum = SubjectsDb::leftjoin('budget_subject AS bs', function ($join) use($input) {
            $join->on('bs.subject_id','=','subjects.sub_id')
                ->where('bs.budget_id', $input['budget_id']);})
            ->where('subjects.status', 1)
            ->select('subjects.sub_id AS id', 'subjects.sub_pid AS pid', 'subjects.sub_name AS subject',
                'subjects.sub_ip AS subject_ip', 'subjects.sub_budget', 'bs.sum_amount AS budget_amount',
                'bs.status AS status')
            ->orderBy('subjects.sub_ip', 'ASC')
            ->get()
            ->toArray();

        //获取预算项金额
        $budget = BudgetSubjectDateDb::select('budget_id', 'subject_id',DB::raw('sum(budget_amount) AS budget_amount'))
            ->whereIn('budget_id', $ids)
            ->where('status', '1')
            ->whereBetween('budget_date_str', array(strtotime($budgetSum['budget_start']), strtotime($budgetSum['budget_end'])))
            ->groupBy('subject_id')
            ->get()
            ->toArray();

        //树形排列科目
        $budgetSum = sortTreeBudget($subjectSum, 0, 0, session('userInfo.sysConfig.budget.subBudget'));
        //倒叙科目汇总金额
        $budgetSum = array_reverse($budgetSum);
        $arrSum = array_column($budgetSum,'pid');
        $arr = array_column($budget,'subject_id');

        //汇总金额
        foreach($budgetSum as $k => $v){
            $budgetSum[$k]['parent'] = ($v['pid'] == 0) ? 1 : 0;
            $budgetSum[$k]['status'] = !$budgetSum[$k]['status'] ? 'false' : $budgetSum[$k]['status'];
            $budgetSum[$k]['budget_amount_child'] = null;
            while (in_array($v['id'], $arr)) {
                $key = array_search($v['id'], $arr);
                if($budget[$key]['budget_amount']){
                    $budgetSum[$k]['budget_amount'] = sprintf("%.2f", $budgetSum[$k]['budget_amount'] + $budget[$key]['budget_amount']);
                    $budgetSum[$k]['budget_amount_child'] = sprintf("%.2f", $budgetSum[$k]['budget_amount_child'] + $budget[$key]['budget_amount']);
                }
                array_pull($arr, $key);
            }
            while (in_array($v['id'], $arrSum)) {
                $key = array_search($v['id'], $arrSum);
                $budgetSum[$k]['budget_amount'] = sprintf("%.2f", $budgetSum[$k]['budget_amount'] + $budgetSum[$key]['budget_amount']);
                $budgetSum[$k]['parent'] = 1;

                array_pull($arrSum, $key);
            }
        }

        $budgetSum = array_reverse($budgetSum);

        //创建结果数据
        $data['data'] = $budgetSum;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }
    
    //获取预算期间
    public function getBudgetSumDate(Request $request)
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

        //获取汇总预算信息
        $budgetSum = BudgetDb::where('budget_id', $input['budget_id'])
                        ->select('budget_ids', 'budget_id', 'budget_start', 'budget_end', 'budget_period')
                        ->get()
                        ->first();

        $ids = $budgetSum['budget_id'].','.$budgetSum['budget_ids'];
        $ids = explode(',', $ids);
        //获取信息
        $budget = BudgetDb::whereIn('budget_id', explode(',', $budgetSum['budget_ids']))
            ->select('budget_id', 'budget_name')
            ->get()
            ->toArray();
        //获取预算期间金额
        $budgetSD = BudgetSubjectDateDb::whereIn('budget_id', $ids)
            ->where('subject_id', $input['subject_id'])
            ->select('budget_date_str', 'budget_amount', 'budget_id')
            ->get()
            ->toArray();

        //获取日期期间
        $monNum = getDateToDiff($budgetSum['budget_start'], $budgetSum['budget_end'], $budgetSum['budget_period']);
        $NowDate = $budgetSum['budget_start'];
        for($i=0; $i <= $monNum; $i++){
            $date[$i]['date'] = $NowDate;
            foreach($ids as $s){
                $date[$i][$s] = 0;
            }
            $NowDate = getNextDate($NowDate, $budgetSum['budget_period']);
        }

        $result = $date;
        //格式化数据
        switch ($budgetSum['budget_period']){
            case 'day':
                $str = 'Y-m-d';
                break;
            case 'month':
                $str = 'Y-m';
                break;
            case 'year':
                $str = 'Y';
                break;
        }
        foreach($date as $k => $d){
            foreach($budgetSD as $b){
                if (strtotime($d['date']) == strtotime(date($str, $b['budget_date_str']))) {
                    $result[$k][$budgetSum['budget_id']] = 0;
                    $result[$k]['date'] = $d['date'];
                    $result[$k][$b['budget_id']] = $result[$k][$b['budget_id']] + $b['budget_amount'];
                    $result[$k][$budgetSum['budget_id']] = $result[$k][$budgetSum['budget_id']] + $result[$k][$b['budget_id']];
                }
            }
            arsort($result[$k]);
        }

        //创建结果数据
        $data['data'] = array('data'=>$result, 'head'=>$budget);
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //查看预算详情
    public function listBudgetSum($id = '0')
    {
        //检测id类型是否整数
        if(!validateParam($id, "nullInt") || $id == '0'){
            return redirectPageMsg('-1', '参数错误', route('budgetSum.index'));
        };

        //获取预算信息
        $budget['budgetSum'] = BudgetDb::where('budget_id', $id)
            ->get()
            ->first();
        if(!$budget['budgetSum']){
            return redirectPageMsg('-1', "参数错误", route('budgetSum.index'));
        }

        //获取子预算
        $budget_ids = explode(',', $budget['budgetSum']['budget_ids']);
        $budget['budget'] = BudgetDb::whereIn('budget_id', $budget_ids)
            ->select('budget_name AS name', 'budget_num AS budget_num')
            ->get()
            ->toArray();
        if(!$budget['budget']){
            return redirectPageMsg('-1', "获取子预算失败，请重新选择", route('budgetSum.index'));
        }

        return view('budgetSum.listBudgetSum', $budget);
    }

    //删除预算
    public function delBudgetSum(Request $request)
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
                        ->where('budget_sum', '0')
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
    public function subBudgetSum(Request $request)
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
            ->where('budget_sum', '1')
            ->get()
            ->first();
        //获取预算项目
        $budgetS = BudgetSubjectDb::where('budget_id',$id)
                            ->where('status','102')
                            ->get()
                            ->first();
        //验证预算
        if(!$budget) echoAjaxJson('-1', '参数错误，预算不存在！');
        if($budget['status'] != "102" && !$budgetS) echoAjaxJson('-1', '提交失败，该预算无审批内容！');

        $whereIn[] = 0;
        $whereIn[] = session('userInfo.dep_id');
        //获取预算审批流程
        $budgetAudit = auditProcessDb::where('audit_type', 'budgetSum')
                                    ->whereIn('audit_dep', $whereIn)
                                    ->get()
                                    ->toArray();
        if($budgetAudit){
            //审批流程大于1则获取对应部门预算
            if(count($budgetAudit) > 1){
                foreach($budgetAudit as $k => $v){
                    if($v['audit_dep'] == session('userInfo.dep_id')){
                        $budgetAudit = $v;
                    }
                }
            }else{
                $budgetAudit = $budgetAudit[0];
            }

            if(!$budgetAudit['audit_process']) echoAjaxJson('-1', '提交失败，审批流程人员获取失败！');

            $result = DB::transaction(function () use($id, $budget, $input, $budgetAudit) {
                $process_users = explode(',', $budgetAudit['audit_process']);
                //审批内容参数
                $auditInfoDb = new auditInfoDb();
                $auditInfoDb->process_type = 'budgetSum';
                $auditInfoDb->process_app = $budget['budget_id'];
                $auditInfoDb->process_title = $input['budget_audit_type'].'—'.$budget['budget_num'].'—'.$budget['budget_name'];
                $auditInfoDb->process_text = $input['process_text'];
                $auditInfoDb->process_users = $budgetAudit['audit_process'];
                $auditInfoDb->process_audit_user = $process_users[0];
                $auditInfoDb->created_user = session('userInfo.user_id');
                $auditInfoDb->status = '1000';
                $auditInfoDb->save();

                //更新预算
                BudgetModel::where('budget_id', $id)
                    ->update(array('status'=>1009));
                //更新预算项目
                BudgetSubjectDb::where('budget_id', $id)
                    ->where('status','102')
                    ->update(array('status'=>1009));
                //更新预算项目金额
                BudgetSubjectDateDb::where('budget_id', $id)
                    ->where('status','102')
                    ->update(array('status'=>1009));
                return true;
            });

            if($result){
                echoAjaxJson('1', '提交成功，请耐心等待审批！');
            }else{
                echoAjaxJson('-1', '审批失败，请重新提交！');
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
        //获取预算
        $budget = BudgetDb::where('budget_id', $id)
                            ->get()
                            ->first();
        $data['budget']['budget_num'] = $budget['budget_num'];
        $data['budget']['budget_name'] = $budget['budget_name'];
        $data['budget']['budget_start'] = $budget['budget_start'];
        $data['budget']['budget_end'] = $budget['budget_end'];
        $data['budget']['status'] = $budget['status'];

        //获取审批流程信息
        $audit = auditInfoDb::where('process_type', 'budget')
                                    ->where('process_app', $id)
                                    ->get()
                                    ->first();
        if($audit['status'] == '1001'){
            echoAjaxJson('-1', '该项目已经结束审审批!');
        }
        $data['audit_user'] = $audit['process_audit_user'];
        //格式化流程
        $audit = explode(',', $audit['process_users']);

        $result = UserDb::leftjoin('users_base AS ub', 'users.user_id', '=', 'ub.user_id')
            ->leftjoin('department AS dep', 'ub.department', '=', 'dep.dep_id')
            ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->select('dep.dep_name', 'pos.pos_name', 'users.user_name', 'users.user_id AS uid')
            ->whereIn('users.user_id', $audit)
            ->get()
            ->toArray();

        //格式化数据
        $data['auditProcess'] = array();
        $data['status'] = 1;
        foreach($audit as $k => $v){
            foreach($result as $u => $d){
                if($v == $d['uid']) $data['auditProcess'][] = $d;
            }
        }

        //返回结果
        ajaxJsonRes($data);
    }
}
