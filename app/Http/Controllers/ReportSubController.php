<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Subjects\SubjectsModel AS SubjectsDb;
use App\Http\Models\Expense\ExpenseMainModel AS ExpenseMainDb;
use App\Http\Models\Contract\ContractMainModel AS ContMainDb;

class ReportSubController extends Common\CommonController
{
    public function index()
    {
        return view('reportSub.index');
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
            'report_date' => 'required',
        ];
        $message = [
            'report_date.required' => '参数不存在',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }
        $iDate = explode(' 一 ', $input['report_date']);
        if(count($iDate) != 2){
            echoAjaxJson('-1', '报表期间错误');
        }

        //获取科目
        $subjects = SubjectsDb::where('status', 1)
            ->select('sub_id AS id', 'sub_ip AS subject_ip', 'sub_pid AS pid',
                'sub_name AS subject_name', 'status')
            ->orderBy('sub_ip', 'ASC')
            ->get()
            ->toArray();
        //树形排列科目
        $result = sortTree($subjects);
        //倒叙科目汇总金额
        $result = array_reverse($result);

        /******************************报销数据***************************/
        //获取报销数据-期初
        $reimburse = ExpenseMainDb::from('expense_main AS em')
            ->leftjoin('expense AS e', 'e.expense_id', '=', 'em.expense_id')
            ->where('e.expense_status', '201')
            ->where('e.expense_date', '<', $iDate[0])
            ->select('subject_id_debit AS debit', 'subject_id_credit AS credit',
                'subject_id_credit AS credit', 'exp_amount AS amount')
            ->get()
            ->toArray();
        //期初数据
        foreach($result as $k => $v){
            $result[$k]['initialDebit'] = 0;
            $result[$k]['initialCredit'] = 0;
            //汇总报销数据
            foreach($reimburse as $kr => $rv){
                if($rv['debit'] == $v['id']){
                    $result[$k]['initialDebit'] = sprintf("%.2f", $result[$k]['initialDebit'] + $rv['amount']);
                }
                if($rv['credit'] == $v['id']){
                    $result[$k]['initialCredit'] = sprintf("%.2f", $result[$k]['initialCredit'] + $rv['amount']);
                }
            }
        }
        //获取报销数据-本期
        $reimburse = ExpenseMainDb::from('expense_main AS em')
            ->leftjoin('expense AS e', 'e.expense_id', '=', 'em.expense_id')
            ->where('e.expense_status', '201')
            ->whereBetween('e.expense_date', $iDate)
            ->select('subject_id_debit AS debit', 'subject_id_credit AS credit',
                'subject_id_credit AS credit', 'exp_amount AS amount')
            ->get()
            ->toArray();
        //本期数据
        foreach($result as $k => $v){
            $result[$k]['currentDebit'] = 0;
            $result[$k]['currentCredit'] = 0;
            //汇总报销数据
            foreach($reimburse as $kr => $rv){
                if($rv['debit'] == $v['id']){
                    $result[$k]['currentDebit'] = sprintf("%.2f", $result[$k]['currentDebit'] + $rv['amount']);
                }
                if($rv['credit'] == $v['id']){
                    $result[$k]['currentCredit'] = sprintf("%.2f", $result[$k]['currentCredit'] + $rv['amount']);
                }
            }
        }
        //获取报销数据-期末
        $reimburse = ExpenseMainDb::from('expense_main AS em')
            ->leftjoin('expense AS e', 'e.expense_id', '=', 'em.expense_id')
            ->where('e.expense_status', '201')
            ->where('e.expense_date', '>', $iDate[1])
            ->select('subject_id_debit AS debit', 'subject_id_credit AS credit',
                'subject_id_credit AS credit', 'exp_amount AS amount')
            ->get()
            ->toArray();
        //期末数据
        foreach($result as $k => $v){
            $result[$k]['lastDebit'] = 0;
            $result[$k]['lastCredit'] = 0;
            //汇总报销数据
            foreach($reimburse as $kr => $rv){
                if($rv['debit'] == $v['id']){
                    $result[$k]['lastDebit'] = sprintf("%.2f", $result[$k]['lastDebit'] + $rv['amount']);
                }
                if($rv['credit'] == $v['id']){
                    $result[$k]['lastCredit'] = sprintf("%.2f", $result[$k]['lastCredit'] + $rv['amount']);
                }
            }
        }

        /******************************合同数据***************************/
        //获取合同数据-期初
        $contract = ContMainDb::from('contract_main AS cm')
            ->leftjoin('contract_details AS cd', 'cd.details_id', '=', 'cm.details_id')
            ->where('cd.cont_details_date', '<', $iDate[0])
            ->select('cd.cont_details_date AS date', 'cd.cont_amount AS amount',
                'cm.subject_id_debit AS debit', 'cm.subject_id_credit AS credit')
            ->get()
            ->toArray();
        //期初数据
        foreach($result as $k => $v){
            $result[$k]['initialDebit'] = 0;
            $result[$k]['initialCredit'] = 0;
            //汇总报销数据
            foreach($contract as $kr => $rv){
                if($rv['debit'] == $v['id']){
                    $result[$k]['initialDebit'] = sprintf("%.2f", $result[$k]['initialDebit'] + $rv['amount']);
                }
                if($rv['credit'] == $v['id']){
                    $result[$k]['initialCredit'] = sprintf("%.2f", $result[$k]['initialCredit'] + $rv['amount']);
                }
            }
        }
        //获取合同数据-本期
        $contract = ContMainDb::from('contract_main AS cm')
            ->leftjoin('contract_details AS cd', 'cd.details_id', '=', 'cm.details_id')
            ->whereBetween('cd.cont_details_date', $iDate)
            ->select('cd.cont_details_date AS date', 'cd.cont_amount AS amount',
                'cm.subject_id_debit AS debit', 'cm.subject_id_credit AS credit')
            ->get()
            ->toArray();
        //本期数据
        foreach($result as $k => $v){
            $result[$k]['currentDebit'] = 0;
            $result[$k]['currentCredit'] = 0;
            //汇总报销数据
            foreach($contract as $kr => $rv){
                if($rv['debit'] == $v['id']){
                    $result[$k]['currentDebit'] = sprintf("%.2f", $result[$k]['currentDebit'] + $rv['amount']);
                }
                if($rv['credit'] == $v['id']){
                    $result[$k]['currentCredit'] = sprintf("%.2f", $result[$k]['currentCredit'] + $rv['amount']);
                }
            }
        }
        //获取合同数据-期末
        $contract = ContMainDb::from('contract_main AS cm')
            ->leftjoin('contract_details AS cd', 'cd.details_id', '=', 'cm.details_id')
            ->where('cd.cont_details_date', '>', $iDate[1])
            ->select('cd.cont_details_date AS date', 'cd.cont_amount AS amount',
                'cm.subject_id_debit AS debit', 'cm.subject_id_credit AS credit')
            ->get()
            ->toArray();
        //期末数据
        foreach($result as $k => $v){
            $result[$k]['lastDebit'] = 0;
            $result[$k]['lastCredit'] = 0;
            //汇总报销数据
            foreach($contract as $kr => $rv){
                if($rv['debit'] == $v['id']){
                    $result[$k]['lastDebit'] = sprintf("%.2f", $result[$k]['lastDebit'] + $rv['amount']);
                }
                if($rv['credit'] == $v['id']){
                    $result[$k]['lastCredit'] = sprintf("%.2f", $result[$k]['lastCredit'] + $rv['amount']);
                }
            }
        }


        //科目汇总
        foreach($result as $k => $v){
            foreach($result as $kk => $vv){
                if($v['id'] == $vv['pid']){
                    $result[$k]['initialDebit'] = sprintf("%.2f", $result[$k]['initialDebit'] + $vv['initialDebit']);
                    $result[$k]['initialCredit'] = sprintf("%.2f", $result[$k]['initialCredit'] + $vv['initialCredit']);
                    $result[$k]['currentDebit'] = sprintf("%.2f", $result[$k]['currentDebit'] + $vv['currentDebit']);
                    $result[$k]['currentCredit'] = sprintf("%.2f", $result[$k]['currentCredit'] + $vv['currentCredit']);
                    $result[$k]['lastDebit'] = sprintf("%.2f", $result[$k]['lastDebit'] + $vv['lastDebit']);
                    $result[$k]['lastCredit'] = sprintf("%.2f", $result[$k]['lastCredit'] + $vv['lastCredit']);
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

}
