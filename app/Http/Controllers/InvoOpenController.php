<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use App\Http\Models\System\SysAssemblyModel AS SysAssDb;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Invoice\InvoiceModel AS InvoiceDb;
use App\Http\Models\Invoice\InvoiceDetailsModel AS InvoiceDetailsDb;

class InvoOpenController extends Common\CommonController
{
    public function index()
    {
        return view('invoOpen.index');
    }
    
    //获取发票列表
    public function getInvoice(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //分页
        $take = !empty($input['length']) ? intval($input['length']) : 10;//数据长度
        $skip = !empty($input['start']) ? intval($input['start']) : 0;//从多少开始

        //获取记录总数
        $total = InvoiceDb::from('invoice AS i')
            ->leftjoin('sys_assembly AS sysAss', 'sysAss.ass_value','=','i.invo_type')
            ->count();
   
        //获取数据
        $result = InvoiceDb::from('invoice AS i')
            ->leftjoin('sys_assembly AS sysAss', 'sysAss.ass_value','=','i.invo_type')
            ->select('i.invo_id AS id', 'i.invo_start_num AS invoice_start_num',
             'i.invo_end_num AS invoice_end_num', 'i.invo_buy_date AS invoice_buy_date',
             'sysAss.ass_text AS invoice_type', 'i.invo_text AS invoice_text')
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

    //添加发票视图
    public function addInvoice()
    {
        //获取合同下拉菜单信息
        $data['select'] = SysAssDb::whereIn('ass_type', array('invoice_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('invoice.addInvoice', $data);
    }
    
    //添加发票
    public function createInvoice()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'invoice_start_num' => 'required|between:0,99999999|numeric',
            'invoice_end_num' => 'required|between:0,99999999|numeric',
            'invoice_buy_date' => 'required|date',
            'invoice_type' => 'required|between:32,32',
            'invoice_text' => 'between:0,200',
        ];
        $message = [
            'invoice_start_num.required' => '请填写发票集-起始号码',
            'invoice_start_num.between' => '发票集-起始号码字符数超出范围',
            'invoice_start_num.numeric' => '发票集必须是数字',
            'invoice_end_num.required' => '请填写发票集-结束号码',
            'invoice_end_num.between' => '发票集-结束号码字符数超出范围',
            'invoice_end_num.numeric' => '发票集必须是数字',
            'invoice_buy_date.required' => '请选择购买日期',
            'invoice_buy_date.date' => '购买日期格式错误',
            'invoice_type.required' => '请选择发票种类',
            'invoice_type.between' => '发票种类参数错误',
            'invoice_text.between' => '备注字符数超出范围',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('invoice.addInvoice'));
        }

        if($input['invoice_start_num'] < 0 || $input['invoice_end_num'] < 0 || $input['invoice_end_num'] < $input['invoice_start_num']){
            return redirectPageMsg('-1', '发票集小于0或者发票集-结束号码小于起始号码', route('invoice.addInvoice'));
        }

        if(strlen($input['invoice_start_num']) != 8 || strlen($input['invoice_end_num']) != 8){
            return redirectPageMsg('-1', '发票集-起始号码或者结束号码不等于8位数', route('invoice.addInvoice'));
        }

        //事物创建数据
        $result = DB::transaction(function () use($input) {
            //创建数据
            $invo_id = getId();
            $data['invo_id'] = $invo_id;
            $data['invo_start_num'] = $input['invoice_start_num'];
            $data['invo_end_num'] = $input['invoice_end_num'];
            $data['invo_buy_date'] = $input['invoice_buy_date'];
            $data['invo_type'] = $input['invoice_type'];
            $data['invo_text'] = $input['invoice_text'];
            $data['created_user'] = session('userInfo.user_id');
            $data['created_at'] = date('Y-m-d H:i:s', time());
            $data['updated_at'] = date('Y-m-d H:i:s', time());
            //创建明细数据发票号
            $invoiceNum = $input['invoice_end_num'] - $input['invoice_start_num'];
            $invoNum[] = $input['invoice_start_num'];
            for($i = 0; $i <= $invoiceNum; $i++){
                $dataDetails[$i]['invo_details_id'] = getId();
                $dataDetails[$i]['invo_id'] = $invo_id;
                $dataDetails[$i]['invo_num'] = formatInvoice($input['invoice_start_num'] + $i);
                $dataDetails[$i]['invo_status'] = '400';
                $dataDetails[$i]['created_at'] = date('Y-m-d H:i:s', time());
                $dataDetails[$i]['updated_at'] = date('Y-m-d H:i:s', time());
            }

            InvoiceDb::insert($data);
            InvoiceDetailsDb::insert($dataDetails);
            return true;
        });

        if($result){
            return redirectPageMsg('1', "提交成功", route('invoice.index'));
        }else{
            return redirectPageMsg('-1', "提交失败", route('invoice.addContract'));
        }
    }

    //删除发票
    public function delInvoice(Request $request)
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

        //检查发票是否存在
        $invoice = InvoiceDb::where('invo_id', $input['id'])
            ->first();
        if(!$invoice){
            echoAjaxJson('-1', '删除失败，发票集不存在');
        }
        //检查发票集是否有使用
        $invoDetails = InvoiceDetailsDb::where('invo_id', $input['id'])
            ->where('invo_status', '!=', '400')
            ->first();
        if($invoDetails){
            echoAjaxJson('-1', '发票集中存在已使用发票无法删除');
        }

        //事物创建数据
        $result = DB::transaction(function () use($input) {
            InvoiceDb::where('invo_id', $input['id'])
                ->delete();
            InvoiceDetailsDb::where('invo_id', $input['id'])
                ->delete();
            return true;
        });

        if($result){
            echoAjaxJson('1', '删除成功');
        }else{
            echoAjaxJson('-1', '删除失败');
        }
    }
    
    //查看详情
    public function listInvoice()
    {
        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.between' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('invoice.index'));
        }

        //发票集是否存在
        $data['invoice'] = InvoiceDb::from('invoice AS i')
            ->leftjoin('sys_assembly AS sysAss', 'sysAss.ass_value','=','i.invo_type')
            ->select('i.invo_id AS id', 'i.invo_start_num AS invoice_start_num',
                'i.invo_end_num AS invoice_end_num', 'i.invo_buy_date AS invoice_buy_date',
                'sysAss.ass_text AS invoice_type', 'i.invo_text AS invoice_text')
            ->where('i.invo_id', $input['id'])
            ->first();
        if(!$data['invoice']){
            return redirectPageMsg('-1', '发票集不存在，请重试', route('invoice.index'));
        }

        //发票总数
        $InvoCount = InvoiceDetailsDb::where('invo_id', $input['id'])
            ->count();
        //发票使用数
        $useInvo = InvoiceDetailsDb::where('invo_id', $input['id'])
            ->where('invo_status', '401')
            ->count();
        $nUseInvo = $InvoCount - $useInvo;//未使用
        $data['invoice']['useInvoRate'] = round(($useInvo / $InvoCount) * 100);//使用率

        return view('invoice.listInvoice', $data);
    }
    
    //获取发票明细列表
    public function getInvoiceDetails(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.between' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }

        //分页
        $take = !empty($input['length']) ? intval($input['length']) : 10;//数据长度
        $skip = !empty($input['start']) ? intval($input['start']) : 0;//从多少开始

        //获取记录总数
        $total = InvoiceDetailsDb::where('invo_id', $input['id'])
            ->count();

        //获取数据
        $result = InvoiceDetailsDb::where('invo_id', $input['id'])
            ->select('invo_details_id AS id', 'invo_num AS invoice_num', 'invo_status AS invoice_status',
                'invo_write_user AS invoice_write_user', 'invo_write_date AS invoice_write_date')
            ->orderBy('invo_num', 'asc')
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

    //删除发票明细
    public function delInvoiceDetails(Request $request)
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

        //检查发票是否存在
        $invoice = InvoiceDetailsDb::where('invo_details_id', $input['id'])
            ->first();
        if(!$invoice){
            echoAjaxJson('-1', '删除失败，发票不存在');
        }

        if($invoice->invo_status != '400'){
            echoAjaxJson('-1', '发票状态不正确无法删除');
        }

        $result = InvoiceDetailsDb::where('invo_details_id', $input['id'])
                ->delete();

        if($result){
            echoAjaxJson('1', '删除成功');
        }else{
            echoAjaxJson('-1', '删除失败');
        }
    }

    //添加发票视图
    public function addInvoiceChild()
    {
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('invoice.index'));
        }

        //检查发票是否存在
        $data['invoice'] = InvoiceDb::where('invo_id', $input['id'])
            ->select('invo_start_num', 'invo_id', 'invo_end_num')
            ->first();
        if(!$data['invoice']){
            return redirectPageMsg('-1', '发票集不存在', route('invoice.index'));
        }

        //获取合同下拉菜单信息
        $data['select'] = SysAssDb::whereIn('ass_type', array('invoice_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('invoice.addInvoiceChild', $data);
    }

    //添加发票
    public function createInvoiceChild()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'invoice_num' => 'required|between:0,99999999|numeric',
            'invoice_id' => 'required|between:32,32',
        ];
        $message = [
            'invoice_num.required' => '请填写发票号码',
            'invoice_num.between' => '发票号码字符数超出范围',
            'invoice_num.numeric' => '发票号码必须是数字',
            'invoice_id.required' => '缺少参数',
            'invoice_id.between' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('invoice.addInvoiceChild'));
        }

        if($input['invoice_num'] < 0){
            return redirectPageMsg('-1', '发票号码错误', route('invoice.addInvoiceChild')."?id=".$input['invoice_id']);
        }

        if(strlen($input['invoice_num']) != 8){
            return redirectPageMsg('-1', '发票号码范围错误', route('invoice.addInvoiceChild')."?id=".$input['invoice_id']);
        }

        //检查发票集是否存在
        $invoice = InvoiceDb::where('invo_id', $input['invoice_id'])
            ->select('invo_start_num', 'invo_id', 'invo_end_num')
            ->first();
        if(!$invoice){
            return redirectPageMsg('-1', '发票集不存在', route('invoice.index'));
        }
        if($invoice['invo_start_num'] > $input['invoice_num'] || $invoice['invo_end_num'] < $input['invoice_num']){
            return redirectPageMsg('-1', '发票号码不能小于发票集-起始号码或大于发票集-结束号码', route('invoice.addInvoiceChild')."?id=".$input['invoice_id']);
        }
        //检查发票号码是否存在
        $invoiceDetails = InvoiceDetailsDb::where('invo_num', formatInvoice($input['invoice_num']))
            ->first();
        if($invoiceDetails){
            return redirectPageMsg('-1', '发票号码已存在', route('invoice.addInvoiceChild')."?id=".$input['invoice_id']);
        }
        $dataDetails['invo_details_id'] = getId();
        $dataDetails['invo_id'] = $input['invoice_id'];
        $dataDetails['invo_num'] = formatInvoice($input['invoice_num']);
        $dataDetails['invo_status'] = '400';
        $dataDetails['created_at'] = date('Y-m-d H:i:s', time());
        $result = InvoiceDetailsDb::insert($dataDetails);

        if($result){
            return redirectPageMsg('1', "提交成功", route('invoice.addInvoiceChild')."?id=".$input['invoice_id']);
        }else{
            return redirectPageMsg('-1', "提交失败", route('invoice.addInvoiceChild')."?id=".$input['invoice_id']);
        }
    }
}
