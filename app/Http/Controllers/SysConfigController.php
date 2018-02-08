<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Models\Subjects\SubjectsModel AS SubjectsDb;
use App\Http\Models\System\SysConfigModel AS SysConfigDb;
use Illuminate\Support\Facades\DB;

class SysConfigController extends Common\CommonController
{
    public function index()
    {
        //树形科目
        $result = SubjectsDb::select('sub_id AS id', 'sub_name AS text', 'sub_pid AS pid', 'sub_ip')
            ->orderBy('sub_ip', 'asc')
            ->get()
            ->toArray();
        //获取最小pid
        $selectPid = SubjectsDb::where('status', 1)
            ->min('sub_pid');
        $result = !getTree($result, $selectPid) ? $result = array() : getTree($result, $selectPid);
        $data['subject'] = json_encode($result);

        //获取配置
        $sysConfig = SysConfigDb::get()
            ->toArray();
        if(!$sysConfig){
            return redirectPageMsg('-1', "获取系统配置失败，请联系管理员！", route('main.index'));
        }

        foreach($sysConfig as $k => $v){
            $data['sysConfig'][$v['sys_class']][$v['sys_type']] = $v['sys_value'];
            $data['sysConfig'][$v['sys_class']][$v['sys_type'].'Text'] = $v['sys_text'];
        }

        if($data['sysConfig']['reimbursePay']['subPay']){
            $arr = explode(',', $data['sysConfig']['reimbursePay']['subPay']);
            if(count($arr) == 2){
                $data['sysConfig']['reimbursePay']['subPay_i'] = $arr['0'];
                $data['sysConfig']['reimbursePay']['subPay_p'] = $arr['1'];
            }else{
                $data['sysConfig']['reimbursePay']['subPay_i'] = '';
                $data['sysConfig']['reimbursePay']['subPay_p'] = '';
            }
            $arrText = explode(',', $data['sysConfig']['reimbursePay']['subPayText']);
            if(count($arrText) == 2){
                $data['sysConfig']['reimbursePay']['subPay_i_text'] = $arrText['0'];
                $data['sysConfig']['reimbursePay']['subPay_p_text'] = $arrText['1'];
            }else{
                $data['sysConfig']['reimbursePay']['subPay_i_text'] = '';
                $data['sysConfig']['reimbursePay']['subPay_p_text'] = '';
            }
        }
        //p($data);
        return view('sysConfig.index', $data);
    }
    
    //更新预算配置
    public function updateBudget()
    {
        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'budget_subBudget' => 'required|between:0,32',
            'budget_subBudget_farm' => 'required'
        ];
        $message = [
            'budget_subBudget.required' => '请选择预算父级科目',
            'budget_subBudget.between' => '参数错误',
            'budget_subBudget_farm.required' => '请选择预算父级科目'
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('sysConfig.index'));
        }

        $data['sys_value'] = $input['budget_subBudget'];
        $data['sys_text'] = $input['budget_subBudget_farm'];
        //更新配置
        $result = SysConfigDb::where('sys_class', 'budget')
            ->where('sys_type', 'subBudget')
            ->update($data);

        if($result){
            return redirectPageMsg('1', "操作成功", route('sysConfig.index'));
        }else{
            return redirectPageMsg('-1', "操作失败", route('sysConfig.index'));
        }
    }

    //更新合同
    public function updateContract()
    {
        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'sub_contract' => 'required|between:0,32',
            'sub_contract_farm' => 'required',
            'uploadNum' => 'required|digits_between:0,30|numeric|integer',
            'uploadSize' => 'required|digits_between:0,20|numeric|integer'
        ];
        $message = [
            'sub_contract.required' => '请选择合同父级科目',
            'sub_contract.between' => '参数错误',
            'sub_contract_farm.required' => '请选择合同父级科目',
            'uploadNum.required' => '请填写附件数量',
            'uploadNum.digits_between' => '附件数量不能大于30',
            'uploadNum.numeric' => '附件数量必须是数字',
            'uploadNum.integer' => '附件数量必须是整数',
            'uploadSize.required' => '请填写附件大小',
            'uploadSize.digits_between' => '附件大小不能大于30',
            'uploadSize.numeric' => '附件大小必须是数字',
            'uploadSize.integer' => '附件大小必须是整数',
        ];

        foreach($input as $k => $v){
            $a = explode('_',$k);
            if($a[0] == 'h'){
                for($i=2;$i<count($a);$i++){
                    if($a[$i] == 'farm'){
                        $rules[$k] = 'required';
                        $message[$k.'.required'] = '合同核销科目未选择';
                    }else{
                        $rules[$k] = 'required|between:32,32';
                        $message[$k.'.required'] = '合同核销科目缺少参数';
                        $message[$k.'.between'] = '合同核销科目参数错误';
                    }
                }
            }
        }

        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('sysConfig.index'));
        }

        foreach($input as $k => $v){
            $a = explode('_',$k);
            if($a[0] == 'h'){
                $x = $a[1];
                for($i=2;$i<count($a);$i++){
                    if($a[$i] == 'farm'){
                        $a[$i] = $a[$i] == 'farm' ? '_farm' : $a[$i];
                    }
                    $x = $x.ucfirst($a[$i]);
                }
                $input[$x] = $v;
                unset($input[$k]);
            }
        }

        $input['budgetOnOff'] = array_key_exists('budgetOnOff', $input) ? 1 : 0;
        $input['subContract'] = $input['sub_contract'];
        $input['subContract_farm'] = $input['sub_contract_farm'];

        unset($input['sub_contract']);
        unset($input['sub_contract_farm']);
        unset($input['_token']);
        
        $result = DB::transaction(function () use($input) {
            foreach($input as $k => $v){
                if (array_key_exists($k.'_farm', $input)) {
                    SysConfigDb::where('sys_class', 'contract')
                        ->where('sys_type', $k)
                        ->update(array('sys_value'=>$v, 'sys_text'=>$input[$k.'_farm']));
                }else{
                    SysConfigDb::where('sys_class', 'contract')
                        ->where('sys_type', $k)
                        ->update(array('sys_value'=>$v));
                }
            }
            return true;
        });

        if($result){
            return redirectPageMsg('1', "操作成功", route('sysConfig.index'));
        }else{
            return redirectPageMsg('-1', "操作失败", route('sysConfig.index'));
        }
    }
    
    //更新费用管理配置
    public function updateReimburse()
    {
        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'reimburse_subReimburse' => 'required|between:0,32',
            'reimburse_subReimburse_farm' => 'required',
            'uploadNum' => 'required|digits_between:0,30|numeric|integer',
            'uploadSize' => 'required|digits_between:0,20|numeric|integer',
            'userCashier' => 'required|between:32,32',
            'userCashier_farm' => 'required',
            'subPay_i' => 'required|between:32,32',
            'subPay_i_farm' => 'required',
            'subPay_p' => 'required|between:32,32',
            'subPay_p_farm' => 'required',
        ];
        $message = [
            'reimburse_subReimburse.required' => '请选择报销费用父级科目',
            'reimburse_subReimburse.between' => '参数错误',
            'reimburse_subReimburse_farm.required' => '请选择报销费用父级科目',
            'uploadNum.required' => '请填写附件数量',
            'uploadNum.digits_between' => '附件数量不能大于30',
            'uploadNum.numeric' => '附件数量必须是数字',
            'uploadNum.integer' => '附件数量必须是整数',
            'uploadSize.required' => '请填写附件大小',
            'uploadSize.digits_between' => '附件大小不能大于30',
            'uploadSize.numeric' => '附件大小必须是数字',
            'uploadSize.integer' => '附件大小必须是整数',
            'userCashier.required' => '请选择出纳',
            'userCashier.between' => '参数错误',
            'userCashier_farm.required' => '请选择出纳',
            'subPay_i.between' => '参数错误',
            'subPay_i_farm.required' => '请选择出纳付款科目-现金',
            'subPay_p.between' => '参数错误',
            'subPay_p_farm.required' => '请选择出纳付款科目-银行',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('sysConfig.index'));
        }
        $input['budgetOnOff'] = array_key_exists('budgetOnOff', $input) ? 1 : 0;
        $input['subReimburse'] = $input['reimburse_subReimburse'];
        $input['subReimburse_farm'] = $input['reimburse_subReimburse_farm'];
        unset($input['reimburse_subReimburse']);
        unset($input['reimburse_subReimburse_farm']);
        unset($input['_token']);
        $inputPay['subPay'] = $input['subPay_i'].','.$input['subPay_p'];
        $inputPay['subPay_farm'] = $input['subPay_i_farm'].','.$input['subPay_p_farm'];
        unset($input['subPay_i_farm']);
        unset($input['subPay_i']);
        unset($input['subPay_p_farm']);
        unset($input['subPay_p']);

        $result = DB::transaction(function () use($input, $inputPay) {
            foreach($input as $k => $v){
                if (array_key_exists($k.'_farm', $input)) {
                    SysConfigDb::where('sys_class', 'reimburse')
                        ->where('sys_type', $k)
                        ->update(array('sys_value'=>$v, 'sys_text'=>$input[$k.'_farm']));
                }else{
                    SysConfigDb::where('sys_class', 'reimburse')
                        ->where('sys_type', $k)
                        ->update(array('sys_value'=>$v));
                }
            }
            foreach($inputPay as $k => $v){
                if (array_key_exists($k.'_farm', $inputPay)) {
                    SysConfigDb::where('sys_class', 'reimbursePay')
                        ->where('sys_type', $k)
                        ->update(array('sys_value'=>$v, 'sys_text'=>$inputPay[$k.'_farm']));
                }else{
                    SysConfigDb::where('sys_class', 'reimbursePay')
                        ->where('sys_type', $k)
                        ->update(array('sys_value'=>$v));
                }
            }
            return true;
        });

        if($result){
            return redirectPageMsg('1', "操作成功", route('sysConfig.index'));
        }else{
            return redirectPageMsg('-1', "操作失败", route('sysConfig.index'));
        }
    }
}
