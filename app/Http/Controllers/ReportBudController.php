<?php

namespace App\Http\Controllers;

use App\Http\Models\Budget\BudgetSubjectModel AS BudgetSubjectDb;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\Budget\BudgetModel AS BudgetDb;
use App\Http\Models\Subjects\SubjectsModel AS SubjectsDb;
use App\Http\Models\Expense\ExpenseMainModel AS ExpenseMainDb;
use App\Http\Models\Contract\ContractMainModel AS ContMainDb;
use App\Http\Models\Budget\BudgetSubjectDateModel AS BudgetSubjectDateDb;
use Illuminate\Support\Facades\DB;

class ReportBudController extends Common\CommonController
{
    public function index()
    {
        return view('reportBud.index');
    }

    //获取报表
    public function getReport(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();
        $rules = [
            'budget_id' => 'required',
        ];
        $message = [
            'budget_id.required' => '参数不存在',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }
        $budget_id = explode(',', $input['budget_id']);

        $subjects = SubjectsDb::where('subjects.status', 1)
            ->select('subjects.sub_id AS id', 'subjects.sub_ip AS subject_ip', 'subjects.sub_pid AS pid',
                'subjects.sub_name AS subject')
            ->orderBy('subjects.sub_ip', 'ASC')
            ->get()
            ->toArray();

        //获取预算金额
        $budget = BudgetSubjectDb::whereIn('budget_id', $budget_id)
            ->where('status', '1')
            ->select('subject_id', DB::raw('sum(sum_amount) AS budget_amount'))
            ->groupBy('subject_id')
            ->get()
            ->toArray();

        //树形排列科目
        $subjects = sortTree($subjects, session('userInfo.sysConfig.budget.subBudget'));

        foreach($subjects as $k => $v){
            $subjects[$k]['budget_amount'] = 0;
            foreach($budget as $bk => $bv){
                if($v['id'] == $bv['subject_id']){
                    $subjects[$k]['budget_amount'] = $subjects[$k]['budget_amount'] + $bv['budget_amount'];
                }
            }
        }

        //获取报销数据
        $reimburse = ExpenseMainDb::from('expense_main AS em')
            ->leftjoin('expense AS e', 'e.expense_id', '=', 'em.expense_id')
            ->where('e.expense_status', '201')
            ->whereIn('em.budget_id', $budget_id)
            ->select('subject_id_debit AS debit', 'subject_id_credit AS credit',
                'subject_id_credit AS credit', 'exp_amount AS amount', 'expense_date AS date')
            ->get()
            ->toArray();

        //获取合同数据
        $contract = ContMainDb::from('contract_main AS cm')
            ->leftjoin('contract_details AS cd', 'cd.details_id', '=', 'cm.details_id')
            ->whereIn('cm.budget_id', $budget_id)
            ->select('cd.cont_details_date AS date', 'cd.cont_amount AS amount',
                'cm.subject_id_debit AS debit', 'cm.subject_id_credit AS credit')
            ->get()
            ->toArray();

        //倒叙科目汇总金额
        $result = array_reverse($subjects);

        foreach($result as $k => $v){
            $result[$k]['report_amount'] = 0;
            //汇总报销数据
            foreach($reimburse as $kr => $rv){
                if($rv['debit'] == $v['id'] || $rv['credit'] == $v['id']){
                    $result[$k]['report_amount'] = sprintf("%.2f", $result[$k]['report_amount'] + $rv['amount']);
                }
            }
            //汇总合同数据
            foreach($contract as $cr => $cv){
                if($cv['debit'] == $v['id'] || $cv['credit'] == $v['id']){
                    $result[$k]['report_amount'] = sprintf("%.2f", $result[$k]['report_amount'] + $cv['amount']);
                }
            }

            $result[$k]['parent'] = ($v['level'] == 0) ? 1 : 0;
            foreach($result as $kk => $vv){
                if($v['id'] == $vv['pid']){
                    $result[$k]['budget_amount'] = sprintf("%.2f", $result[$k]['budget_amount'] + $vv['budget_amount']);
                    $result[$k]['report_amount'] = sprintf("%.2f", $result[$k]['report_amount'] + $vv['report_amount']);
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
    public function getReportDate(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();
        $rules = [
            'budget_id' => 'required',
            'subject_id' => 'required|between:32,32',
        ];
        $message = [
            'budget_id.required' => '参数不存在',
            'budget_id.between' => '参数错误',
            'subject_id.required' => '科目参数不存在',
            'subject_id.between' => '科目参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }

        $budget_id = explode(',', $input['budget_id']);

        //获取预算金额
        $dateAmount = BudgetSubjectDateDb::whereIn('budget_id', $budget_id)
            ->where('subject_id', $input['subject_id'])
            ->select('budget_date', 'budget_amount')
            ->get()
            ->toArray();

        //获取预算期间
        $dateBudget = BudgetSubjectDateDb::whereIn('budget_id', $budget_id)
            ->where('subject_id', $input['subject_id'])
            ->select(DB::raw('max(budget_date) AS max_date'), DB::raw('min(budget_date) AS min_date'))
            ->get()
            ->toArray();

        if(strlen($dateBudget[0]['max_date']) == '10' && strlen($dateBudget[0]['min_date']) == '10'){
            foreach($dateAmount as $k => $v){
                $aDate[] = $v['budget_date'];
            }
        }else{
            foreach($dateAmount as $k => $v){
                $aDate[] = substr($v['budget_date'], 0, 7);
            }
        }
        //去重
        $aDate = array_unique($aDate);
        sort($aDate);
        //格式化数据
        foreach($aDate as $k => $v){
            $result[$k]['budget_date'] = $v;
            $result[$k]['budget_amount'] = 0;
            foreach($dateAmount as $rk => $rv){
                if(strlen($dateBudget[0]['max_date']) == 10 && strlen($dateBudget[0]['min_date']) == 10){
                    if($v == $rv['budget_date']){
                        $result[$k]['budget_date'] = $v;
                        $result[$k]['budget_amount'] = $result[$k]['budget_amount'] + $rv['budget_amount'];
                    }
                }else{
                    if($v == substr($rv['budget_date'], 0, 7)){
                        $result[$k]['budget_date'] = $v;
                        $result[$k]['budget_amount'] = $result[$k]['budget_amount'] + $rv['budget_amount'];
                    }
                }
            }
        }

        //获取报销数据
        $reimburse = ExpenseMainDb::from('expense_main AS em')
            ->leftjoin('expense AS e', 'e.expense_id', '=', 'em.expense_id')
            ->where('e.expense_status', '201')
            ->whereIn('em.budget_id', $budget_id)
            ->Where(function ($query) use($input) {
                $query->where('em.subject_id_debit', $input['subject_id'])
                    ->orWhere('em.subject_id_credit', $input['subject_id']);
            })
            ->select('exp_amount AS amount', 'expense_date AS date')
            ->get()
            ->toArray();

        //获取合同数据
        $contract = ContMainDb::from('contract_main AS cm')
            ->leftjoin('contract_details AS cd', 'cd.details_id', '=', 'cm.details_id')
            ->whereIn('cm.budget_id', $budget_id)
            ->where(function ($query) use($input) {
                $query->where('subject_id_debit', $input['subject_id'])
                    ->orWhere('subject_id_credit', $input['subject_id']);
            })
            ->select('cd.cont_details_date AS date', 'cd.cont_amount AS amount')
            ->get()
            ->toArray();

        foreach($result as $k => $v){
            $result[$k]['report_amount'] = 0;
            if($reimburse){
                foreach($reimburse as $rk => $rv){
                    if(strlen($dateBudget[0]['max_date']) == '10' && strlen($dateBudget[0]['min_date']) == '10'){
                        if($v['budget_date'] == $rv['date']){
                            $result[$k]['report_amount'] = $result[$k]['report_amount'] + $rv['amount'];
                        }
                    }else{
                        if($v['budget_date'] == substr($rv['date'], 0, 7)) {
                            $result[$k]['report_amount'] = $result[$k]['report_amount'] + $rv['amount'];
                        }
                    }
                }
            }
            if($contract){
                foreach($contract as $rk => $rv){
                    if(strlen($dateBudget[0]['max_date']) == 10 && strlen($dateBudget[0]['min_date']) == 10){
                        if($v['budget_date'] == $rv['date']){
                            $result[$k]['report_amount'] = $result[$k]['report_amount'] + $rv['amount'];
                        }
                    }else{
                        if($v['budget_date'] == substr($rv['date'], 0, 7)) {
                            $result[$k]['report_amount'] = $result[$k]['report_amount'] + $rv['amount'];
                        }
                    }
                }
            }
        }
        
        //创建结果数据
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }
}
