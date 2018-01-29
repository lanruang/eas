<?php

namespace App\Http\Controllers;

use App\Http\Models\Contract\ContDetailsModel AS ContDetailsDb;
use App\Http\Models\Customer\CustomerModel AS CustomerDb;
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

    //开具发票
    public function createInvoOpen()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'contInfo_id' => 'required',
            'invoInfo_id' => 'required',
            'customer_id' => 'required|between:32,32',
        ];
        $message = [
            'contInfo_id.required' => '请选择合同',
            'invoInfo_id.required' => '请选择发票',
            'customer_id.required' => '请选择客户',
            'customer_id.between' => '客户参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('invoOpen.index'));
        }

        //客户是否存在
        $customer = CustomerDb::where('cust_id', $input['customer_id'])
            ->first();
        if(!$customer){
            return redirectPageMsg('-1', '客户不存在', route('invoOpen.index'));
        }
        //查询发票号码是否存在
        $invoice = InvoiceDetailsDb::where('invo_details_id', $input['invoInfo_id'])
            ->where('invo_status', '400')
            ->first();
        if(!$invoice){
            return redirectPageMsg('-1', '发票不存在，或已使用', route('invoOpen.index'));
        }
        //查询合同详情是否存在
        $details_id = explode(',', $input['contInfo_id']);
        $contract = ContDetailsDb::whereIn('details_id', $details_id)
            ->where('cont_status', '302')
            ->get()
            ->toArray();
        if(count($contract) != count($details_id)){
            return redirectPageMsg('-1', '可开票合同期间数量不匹配', route('invoOpen.index'));
        }
        //格式化合同数据
        foreach($contract as $k => $v){
            $cont[$v['details_id']] = $v['cont_amount'];
        }

        //创建数据
        foreach($details_id as $k => $v){
            $data[$k]['invo_main_id'] = getId();
            $data[$k]['invo_num'] = $invoice->invo_num;
            $data[$k]['invo_main_type'] = 'contIncome';
            if(array_key_exists($v, $cont)){
                $data[$k]['invo_amount'] = $cont[$v];
            }else{
                return redirectPageMsg('-1', '系统错误，请刷新后重试', route('invoOpen.index'));
            }
            $data[$k]['invo_text'] = $invoice['invo_text'];
            $data[$k]['invo_parties'] = $customer['cust_id'];
            $data[$k]['invo_tax_num'] = '';
            $data[$k]['invo_cont_id'] = $k;
            $data[$k]['created_user'] = date('Y-m-d H:i:s', time());
            $data[$k]['updated_at'] = date('Y-m-d H:i:s', time());
        }

        p($data);

    }
}
