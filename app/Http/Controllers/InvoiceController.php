<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use App\Http\Models\System\SysAssemblyModel AS SysAssDb;
use Validator;

class InvoiceController extends Common\CommonController
{
    public function index()
    {
        return view('invoice.index');
    }
    
    //消息通知
    public function getInvoice(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取参数
        $input = Input::all();

        //搜索参数
        $searchSql[] = array('notice_user', session('userInfo.user_id'));

        //获取数据
        $result = NoticeDb::where($searchSql)
            ->select('notice_id', 'notice_message', 'created_at AS add_time', 'is_see AS see')
            ->limit(5)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    
        //创建结果数据
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

        //创建发票号
        $invoiceNum = $input['invoice_end_num'] - $input['invoice_start_num'];
        $invoNum[] = $input['invoice_start_num'];
        for($i = 1; $i <= $invoiceNum; $i++){
            $invoNum[] = $input['invoice_start_num'] + $i;
        }
        p($invoNum);



    }
}
