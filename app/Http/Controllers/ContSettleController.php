<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\Contract\ContractModel AS ContractDb;
use App\Http\Models\Contract\ContDetailsModel AS ContDetailsDb;
use App\Http\Models\Contract\ContEnclosureModel AS ContEncloDb;
use App\Http\Models\Contract\ContractMainModel AS ContMainDb;
use App\Http\Models\System\SysAssemblyModel AS SysAssDb;
use App\Http\Models\Subjects\SubjectsModel AS SubjectsDb;
use App\Http\Models\Customer\CustomerModel AS CustomerDb;
use App\Http\Models\Supplier\SupplierModel AS SupplierDb;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Http\Models\AuditProcess\AuditProcessModel AS AuditProcessDb;
use App\Http\Models\AuditProcess\AuditInfoModel AS AuditInfoDb;
use App\Http\Models\AuditProcess\AuditInfoTextModel AS AuditInfoTextDb;
use App\Http\Models\User\UserModel AS UserDb;
use Validator;
use Storage;

class ContSettleController extends Common\CommonController
{
    //合同结算视图
    public function index()
    {
        //获取合同下拉菜单信息
        $data['select'] = SysAssDb::whereIn('ass_type', array('contract_class', 'contract_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('contSettle.index', $data);
    }

    //合同结算列表
    public function getSettle(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //验证表单
        $input = Input::all();
        $searchSql = array();
        if(array_key_exists('contract_class', $input) && $input['contract_class']){
            $searchSql[] = array('c.cont_class', $input['contract_class']);
        }
        if(array_key_exists('contract_type', $input) && $input['contract_type']){
            $searchSql[] = array('c.cont_type', $input['contract_type']);
        }
        if(array_key_exists('contract_num', $input) && $input['contract_num']){
            $searchSql[] = array('c.cont_num', 'like', '%' . $input['contract_num'] . '%');
        }
        if(array_key_exists('contract_name', $input) && $input['contract_name']){
            $searchSql[] = array('c.cont_name', 'like', '%' . $input['contract_name'] . '%');
        }
        if(array_key_exists('contract_date', $input) && $input['contract_date']){
            $iDate = explode(' 一 ', $input['contract_date']);
            if(count($iDate) != 2){
                $data['status'] = -1;
                $data['msg'] = '合同期间错误';
                ajaxJsonRes($data);
            }
            $searchSql[] = array('cd.cont_details_date', '>=', $iDate[0]);
            $searchSql[] = array('cd.cont_details_date', '<=', $iDate[1]);
        }
        if(array_key_exists('supplier_name', $input) && $input['supplier_name']){
            $searchSql[] = array('supp.supp_name', 'like', '%' . $input['supplier_name'] . '%');
        }
        if(array_key_exists('customer_name', $input) && $input['customer_name']){
            $searchSql[] = array('cust.cust_name', 'like', '%' . $input['customer_name'] . '%');
        }


        //获取记录总数
        $total = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'cd.cont_id', '=', 'c.cont_id')
            ->leftJoin('sys_assembly AS sysAssType', 'c.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'c.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'c.cont_parties','=','cust.cust_id')
            ->leftJoin('supplier AS supp', 'c.cont_parties','=','supp.supp_id')
            ->where($searchSql)
            ->count();
        //获取数据
        $result = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'cd.cont_id', '=', 'c.cont_id')
            ->leftJoin('sys_assembly AS sysAssType', 'c.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'c.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'c.cont_parties','=','cust.cust_id')
            ->leftJoin('supplier AS supp', 'c.cont_parties','=','supp.supp_id')
            ->where($searchSql)
            ->select('cd.cont_details_date AS contract_details_date', 'cd.cont_amount AS contract_amount',
                'sysAssType.ass_text AS contract_type', 'sysAssClass.ass_text AS contract_class',
                'cust.cust_name AS customer_name', 'supp.supp_name AS supplier_name', 'c.cont_name AS contract_name','c.cont_num AS contract_num')
            ->get()
            ->toArray();

        //创建结果数据
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    /******************************************收入合同****************************************/
    //合同应收视图
    public function receivable()
    {
        return view('contSettle.receivable');
    }
    
    //合同应收列表
    public function getReceivable(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'cd.cont_id', '=', 'c.cont_id')
            ->leftJoin('sys_assembly AS sysAssType', 'c.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'c.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'c.cont_parties','=','cust.cust_id')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.income'))
            ->where('cd.cont_handle_status', '100')
            ->count();
        //获取数据
        $result = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'cd.cont_id', '=', 'c.cont_id')
            ->leftJoin('sys_assembly AS sysAssType', 'c.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'c.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'c.cont_parties','=','cust.cust_id')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.income'))
            ->where('cd.cont_handle_status', '100')
            ->select('cd.cont_details_date AS contract_details_date', 'cd.cont_amount AS contract_amount',
                'sysAssType.ass_text AS contract_type', 'sysAssClass.ass_text AS contract_class',
                'cust.cust_name AS customer_name', 'c.cont_name AS contract_name','c.cont_num AS contract_num')
            ->get()
            ->toArray();

        //创建结果数据
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }
    
    //生成合同应收
    public function createReceivable()
    {
        if(!session('userInfo.sysConfig.contract.receivableSubDebit') || !session('userInfo.sysConfig.contract.receivableSubCredit')){
            return redirectPageMsg('-1', '合同核销-合同应收科目未设置，请先设置科目', route('contSettle.receivable'));
        }

        $nowDate = date('Y-m-d', time());
        //获取生成数据
        $contDetails = ContDetailsDb::leftjoin('contract AS c', 'contract_details.cont_id', '=', 'c.cont_id')
            ->select('contract_details.*', 'c.cont_budget')
            ->where('contract_details.cont_details_date', '<=' , $nowDate)
            ->where('contract_details.cont_status', '301')
            ->where('contract_details.cont_handle_status', '000')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.income'))
            ->take(50)
            ->get()
            ->toArray();
        if(!$contDetails){
            return redirectPageMsg('1', '没有应收款生成', route('contSettle.receivable'));
        }
        //格式化数据
        foreach($contDetails as $k => $v){
            $data[$k]['cont_main_id'] = getId();
            $data[$k]['cont_main_type'] = 'receivable';
            $data[$k]['cont_id'] = $v['cont_id'];
            $data[$k]['details_id'] = $v['details_id'];
            $data[$k]['cont_amount'] = $v['cont_amount'];
            $data[$k]['budget_id'] = $v['cont_budget'];
            $data[$k]['subject_id_debit'] = session('userInfo.sysConfig.contract.receivableSubDebit');
            $data[$k]['subject_id_credit'] = session('userInfo.sysConfig.contract.receivableSubCredit');
            $data[$k]['created_user'] = session('userInfo.user_id');
            $data[$k]['created_at'] = date('Y-m-d H:i:s', time());
            $data[$k]['updated_at'] = date('Y-m-d H:i:s', time());
            $ids[] = $v['details_id'];
        }

        //事务处理
        $result = DB::transaction(function () use($data, $ids) {
            ContDetailsDb::whereIn('details_id', $ids)
                ->update(['cont_handle_status' => '100']);
            ContMainDb::insert($data);

            return true;
        });

        if($result){
            return redirectPageMsg('1', "生成".count($ids)."条记录成功", route('contSettle.receivable'));
        }else{
            return redirectPageMsg('-1', "生成失败", route('contSettle.receivable'));
        }
    }

    //合同收入结算视图
    public function income()
    {
        return view('contSettle.income');
    }
    
    //合同收入列表
    public function getIncome(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'cd.cont_id', '=', 'c.cont_id')
            ->leftJoin('sys_assembly AS sysAssType', 'c.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'c.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'c.cont_parties','=','cust.cust_id')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.income'))
            ->where('cd.cont_handle_status', '110')
            ->count();
        //获取数据
        $result = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'cd.cont_id', '=', 'c.cont_id')
            ->leftJoin('sys_assembly AS sysAssType', 'c.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'c.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'c.cont_parties','=','cust.cust_id')
            ->leftJoin('invoice_main AS im', 'cd.details_id','=','im.invo_cont_id')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.income'))
            ->where('cd.cont_handle_status', '110')
            ->select('cd.details_id AS id', 'cd.cont_details_date AS contract_details_date', 'cd.cont_amount AS contract_amount',
                'sysAssType.ass_text AS contract_type', 'sysAssClass.ass_text AS contract_class',
                'cust.cust_name AS customer_name', 'c.cont_name AS contract_name','c.cont_num AS contract_num',
                'im.invo_num AS invoice_num')
            ->get()
            ->toArray();

        //创建结果数据
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //合同收入结算
    public function createIncome()
    {
        if(!session('userInfo.sysConfig.contract.incomeSubDebit') || !session('userInfo.sysConfig.contract.incomeSubCredit') || !session('userInfo.sysConfig.contract.incomeAutoSubDebit')){
            return redirectPageMsg('-1', '合同核销-合同确认收款或自动结算未设置，请先设置科目', route('contSettle.income'));
        }

        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'ids' => 'required',
        ];
        $message = [
            'ids.required' => '请选择合同期间',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('contSettle.income'));
        }

        $ids = explode(',', $input['ids']);
        if(count($ids) > 50){
            return redirectPageMsg('-1', '合同结算数量一次不能超过50条记录,请重新选择', route('contSettle.income'));
        }

        $contDetails = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'c.cont_id', '=', 'cd.cont_id')
            ->whereIn('cd.details_id', $ids)
            ->where('c.cont_class', session('userInfo.sysConfig.contract.income'))
            ->select('cd.*', 'c.cont_subject', 'c.cont_budget')
            ->get()
            ->toArray();
        if(count($ids) != count($contDetails)){
            return redirectPageMsg('-1', '合同期间数量不符,请刷新后重试', route('contSettle.income'));
        }

        //创建数据
        $x = 0;
        foreach($contDetails as $k => $v){
            //收款核算
            $data[$x]['cont_main_id'] = getId();
            $data[$x]['cont_main_type'] = 'income';
            $data[$x]['cont_id'] = $v['cont_id'];
            $data[$x]['details_id'] = $v['details_id'];
            $data[$x]['cont_amount'] = $v['cont_amount'];
            $data[$x]['budget_id'] = $v['cont_budget'];
            $data[$x]['subject_id_debit'] = session('userInfo.sysConfig.contract.incomeSubDebit');
            $data[$x]['subject_id_credit'] = session('userInfo.sysConfig.contract.incomeSubCredit');
            $data[$x]['created_user'] = session('userInfo.user_id');
            $data[$x]['created_at'] = date('Y-m-d H:i:s', time());
            $data[$x]['updated_at'] = date('Y-m-d H:i:s', time());
            $x++;
            //自动结转
            $data[$x]['cont_main_id'] = getId();
            $data[$x]['cont_main_type'] = 'incomeAuto';
            $data[$x]['cont_id'] = $v['cont_id'];
            $data[$x]['details_id'] = $v['details_id'];
            $data[$x]['cont_amount'] = $v['cont_amount'];
            $data[$x]['budget_id'] = $v['cont_budget'];
            $data[$x]['subject_id_debit'] = session('userInfo.sysConfig.contract.incomeAutoSubDebit');
            $data[$x]['subject_id_credit'] = $v['cont_subject'];
            $data[$x]['created_user'] = session('userInfo.user_id');
            $data[$x]['created_at'] = date('Y-m-d H:i:s', time());
            $data[$x]['updated_at'] = date('Y-m-d H:i:s', time());
            $x++;
        }

        //事务处理
        $result = DB::transaction(function () use($data, $ids) {
            ContDetailsDb::whereIn('details_id', $ids)
                ->update(['cont_handle_status' => '111']);
            ContMainDb::insert($data);

            return true;
        });

        if($result){
            return redirectPageMsg('1', "结算".count($ids)."条记录成功", route('contSettle.income'));
        }else{
            return redirectPageMsg('-1', "结算失败", route('contSettle.income'));
        }
    }

    /******************************************付款合同****************************************/
    //合同应付视图
    public function payable()
    {
        return view('contSettle.payable');
    }

    //合同应付列表
    public function getPayable(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'cd.cont_id', '=', 'c.cont_id')
            ->leftJoin('sys_assembly AS sysAssType', 'c.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'c.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'c.cont_parties','=','cust.cust_id')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.payment'))
            ->count();
        //获取数据
        $result = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'cd.cont_id', '=', 'c.cont_id')
            ->leftJoin('sys_assembly AS sysAssType', 'c.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'c.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'c.cont_parties','=','cust.cust_id')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.payment'))
            ->select('cd.cont_details_date AS contract_details_date', 'cd.cont_amount AS contract_amount',
                'sysAssType.ass_text AS contract_type', 'sysAssClass.ass_text AS contract_class',
                'cust.cust_name AS customer_name', 'c.cont_name AS contract_name','c.cont_num AS contract_num')
            ->get()
            ->toArray();

        //创建结果数据
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //生成合同应付
    public function createPayable()
    {
        if(!session('userInfo.sysConfig.contract.payableSubDebit') || !session('userInfo.sysConfig.contract.payableSubCredit')){
            return redirectPageMsg('-1', '合同核销-合同应付科目未设置，请先设置科目', route('contSettle.payable'));
        }

        $nowDate = date('Y-m-d', time());
        //获取生成数据
        $contDetails = ContDetailsDb::leftjoin('contract AS c', 'contract_details.cont_id', '=', 'c.cont_id')
            ->select('contract_details.*', 'c.cont_budget')
            ->where('contract_details.cont_details_date', '<=' , $nowDate)
            ->where('contract_details.cont_status', '301')
            ->where('contract_details.cont_handle_status', '100')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.payment'))
            ->take(50)
            ->get()
            ->toArray();
        if(!$contDetails){
            return redirectPageMsg('1', '没有应付款生成', route('contSettle.payable'));
        }
        //格式化数据
        foreach($contDetails as $k => $v){
            $data[$k]['cont_main_id'] = getId();
            $data[$k]['cont_main_type'] = 'payable';
            $data[$k]['cont_id'] = $v['cont_id'];
            $data[$k]['details_id'] = $v['details_id'];
            $data[$k]['cont_amount'] = $v['cont_amount'];
            $data[$k]['budget_id'] = $v['cont_budget'];
            $data[$k]['subject_id_debit'] = session('userInfo.sysConfig.contract.payableSubDebit');
            $data[$k]['subject_id_credit'] = session('userInfo.sysConfig.contract.payableSubCredit');
            $data[$k]['created_user'] = session('userInfo.user_id');
            $data[$k]['created_at'] = date('Y-m-d H:i:s', time());
            $data[$k]['updated_at'] = date('Y-m-d H:i:s', time());
            $ids[] = $v['details_id'];
        }

        //事务处理
        $result = DB::transaction(function () use($data, $ids) {
            ContDetailsDb::whereIn('details_id', $ids)
                ->update(['cont_handle_status' => '100']);
            ContMainDb::insert($data);

            return true;
        });

        if($result){
            return redirectPageMsg('1', "生成".count($ids)."条记录成功", route('contSettle.payable'));
        }else{
            return redirectPageMsg('-1', "生成失败", route('contSettle.payable'));
        }
    }

    //合同收入结算视图
    public function payment()
    {
        return view('contSettle.payment');
    }

    //合同付款列表
    public function getPayment(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'cd.cont_id', '=', 'c.cont_id')
            ->leftJoin('sys_assembly AS sysAssType', 'c.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'c.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'c.cont_parties','=','cust.cust_id')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.payment'))
            ->where('cd.cont_handle_status', '110')
            ->count();
        //获取数据
        $result = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'cd.cont_id', '=', 'c.cont_id')
            ->leftJoin('sys_assembly AS sysAssType', 'c.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'c.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'c.cont_parties','=','cust.cust_id')
            ->leftJoin('invoice_main AS im', 'cd.details_id','=','im.invo_cont_id')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.payment'))
            ->where('cd.cont_handle_status', '110')
            ->select('cd.details_id AS id', 'cd.cont_details_date AS contract_details_date', 'cd.cont_amount AS contract_amount',
                'sysAssType.ass_text AS contract_type', 'sysAssClass.ass_text AS contract_class',
                'cust.cust_name AS customer_name', 'c.cont_name AS contract_name','c.cont_num AS contract_num',
                'im.invo_num AS invoice_num')
            ->get()
            ->toArray();

        //创建结果数据
        $data['recordsTotal'] = $total;//总记录数
        $data['recordsFiltered'] = $total;//条件过滤后记录数
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //合同付款结算
    public function createPayment()
    {
        if(!session('userInfo.sysConfig.contract.paymentSubDebit') || !session('userInfo.sysConfig.contract.paymentSubCredit') || !session('userInfo.sysConfig.contract.paymentAutoSubDebit')){
            return redirectPageMsg('-1', '合同核销-合同确认付款或自动结算未设置，请先设置科目', route('contSettle.income'));
        }

        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'ids' => 'required',
        ];
        $message = [
            'ids.required' => '请选择合同期间',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('contSettle.payment'));
        }

        $ids = explode(',', $input['ids']);
        if(count($ids) > 50){
            return redirectPageMsg('-1', '合同结算数量一次不能超过50条记录,请重新选择', route('contSettle.payment'));
        }

        $contDetails = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'c.cont_id', '=', 'cd.cont_id')
            ->whereIn('cd.details_id', $ids)
            ->where('c.cont_class', session('userInfo.sysConfig.contract.payment'))
            ->select('cd.*', 'c.cont_subject', 'c.cont_budget')
            ->get()
            ->toArray();

        if(count($ids) != count($contDetails)){
            return redirectPageMsg('-1', '合同期间数量不符,请刷新后重试', route('contSettle.payment'));
        }

        //创建数据
        $x = 0;
        foreach($contDetails as $k => $v){
            //收款核算
            $data[$x]['cont_main_id'] = getId();
            $data[$x]['cont_main_type'] = 'payment';
            $data[$x]['cont_id'] = $v['cont_id'];
            $data[$x]['details_id'] = $v['details_id'];
            $data[$x]['cont_amount'] = $v['cont_amount'];
            $data[$x]['budget_id'] = $v['cont_budget'];
            $data[$x]['subject_id_debit'] = session('userInfo.sysConfig.contract.paymentSubDebit');
            $data[$x]['subject_id_credit'] = session('userInfo.sysConfig.contract.paymentSubCredit');
            $data[$x]['created_user'] = session('userInfo.user_id');
            $data[$x]['created_at'] = date('Y-m-d H:i:s', time());
            $data[$x]['updated_at'] = date('Y-m-d H:i:s', time());
            $x++;
            //自动结转
            $data[$x]['cont_main_id'] = getId();
            $data[$x]['cont_main_type'] = 'paymentAuto';
            $data[$x]['cont_id'] = $v['cont_id'];
            $data[$x]['details_id'] = $v['details_id'];
            $data[$x]['cont_amount'] = $v['cont_amount'];
            $data[$x]['budget_id'] = $v['cont_budget'];
            $data[$x]['subject_id_debit'] = session('userInfo.sysConfig.contract.paymentAutoSubDebit');
            $data[$x]['subject_id_credit'] = $v['cont_subject'];
            $data[$x]['created_user'] = session('userInfo.user_id');
            $data[$x]['created_at'] = date('Y-m-d H:i:s', time());
            $data[$x]['updated_at'] = date('Y-m-d H:i:s', time());
            $x++;
        }

        //事务处理
        $result = DB::transaction(function () use($data, $ids) {
            ContDetailsDb::whereIn('details_id', $ids)
                ->update(['cont_handle_status' => '111']);
            ContMainDb::insert($data);

            return true;
        });

        if($result){
            return redirectPageMsg('1', "结算".count($ids)."条记录成功", route('contSettle.payment'));
        }else{
            return redirectPageMsg('-1', "结算失败", route('contSettle.payment'));
        }
    }
}
