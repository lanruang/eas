<?php

namespace App\Http\Controllers;

use App\Http\Models\Contract\ContDetailsModel AS ContDetailsDb;
use App\Http\Models\Contract\ContractMainModel AS ContMainDb;
use App\Http\Models\Contract\ContractModel AS ContDb;
use App\Http\Models\Customer\CustomerModel AS CustomerDb;
use App\Http\Models\Supplier\SupplierModel AS SupplierDb;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Input;
use App\Http\Models\System\SysAssemblyModel AS SysAssDb;
use Validator;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Invoice\InvoiceModel AS InvoiceDb;
use App\Http\Models\Invoice\InvoiceDetailsModel AS InvoiceDetailsDb;
use App\Http\Models\Invoice\InvoiceMainModel AS InvoMainDb;

class InvoCollectController extends Common\CommonController
{
    public function index()
    {
        //获取合同下拉菜单信息
        $data['select'] = SysAssDb::whereIn('ass_type', array('invoice_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('invoCollect.index', $data);
    }

    //开具发票
    public function createInvoCollect()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'contInfo_id' => 'required',
            'supplier_id' => 'required|between:32,32',
            'invoice_num' => 'required|between:0,99999999|numeric',
            'invoice_type' => 'required|between:32,32',
            'invoice_text' => 'between:0,200',
        ];
        $message = [
            'contInfo_id.required' => '请选择合同',
            'invoInfo_id.required' => '请选择发票',
            'supplier_id.required' => '请选择供应商',
            'supplier_id.between' => '供应商参数错误',
            'invoice_num.required' => '请填写发票号码',
            'invoice_num.between' => '发票字符数超出范围',
            'invoice_num.numeric' => '发票必须是数字',
            'invoice_type.required' => '请选择发票种类',
            'invoice_type.between' => '发票种类参数错误',
            'invoice_text.between' => '备注字符数超出范围',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('invoOpen.index'));
        }

        //供应商是否存在
        $supplier = SupplierDb::where('supp_id', $input['supplier_id'])
            ->first();
        if(!$supplier){
            return redirectPageMsg('-1', '供应商不存在', route('invoCollect.index'));
        }

        //查询合同详情是否存在
        $details_id = explode(',', $input['contInfo_id']);
        $contract = ContDetailsDb::from('contract_details AS cd')
            ->leftjoin('contract AS c', 'c.cont_id', '=', 'cd.cont_id')
            ->whereIn('cd.details_id', $details_id)
            ->where('cd.cont_status', '301')
            ->where('cd.cont_handle_status', '100')
            ->where('c.cont_status', '301')
            ->select('cd.*', 'c.cont_budget AS budget_id')
            ->where('c.cont_class', session('userInfo.sysConfig.contract.payment'))
            ->get()
            ->toArray();

        if(!$contract){
            return redirectPageMsg('-1', '合同期间不存在', route('invoCollect.index'));
        }
        if(count($contract) != count($details_id)){
            return redirectPageMsg('-1', '可开票合同期间数量不匹配', route('invoCollect.index'));
        }

        //创建签票数据
        foreach($contract as $k => $v){
            $data['dataInvo'][$k]['invo_main_id'] = getId();
            $data['dataInvo'][$k]['invo_num'] = $input['invoice_num'];
            $data['dataInvo'][$k]['invo_main_type'] = 'contCollect';
            $data['dataInvo'][$k]['invo_amount'] = $v['cont_amount'];
            $data['dataInvo'][$k]['invo_text'] = $input['invoice_text'];
            $data['dataInvo'][$k]['invo_parties'] = $supplier['supp_id'];
            $data['dataInvo'][$k]['invo_tax_num'] = '';
            $data['dataInvo'][$k]['invo_cont_id'] = $v['details_id'];
            $data['dataInvo'][$k]['created_at'] = date('Y-m-d H:i:s', time());
            $data['dataInvo'][$k]['updated_at'] = date('Y-m-d H:i:s', time());
            //创建合同核销数据
            $data['dataContMain'][$k]['cont_main_id'] = getId();
            $data['dataContMain'][$k]['cont_main_type'] = 'invoCollect';
            $data['dataContMain'][$k]['cont_id'] = $v['cont_id'];
            $data['dataContMain'][$k]['details_id'] = $v['details_id'];
            $data['dataContMain'][$k]['cont_amount'] = $v['cont_amount'];
            $data['dataContMain'][$k]['budget_id'] = $v['budget_id'];
            $data['dataContMain'][$k]['subject_id_debit'] = session('userInfo.sysConfig.contract.invoOpenSubDebit');
            $data['dataContMain'][$k]['subject_id_credit'] = session('userInfo.sysConfig.contract.invoOpenSubCredit');
            $data['dataContMain'][$k]['created_user'] = session('userInfo.user_id');
            $data['dataContMain'][$k]['created_at'] = date('Y-m-d H:i:s', time());
            $data['dataContMain'][$k]['updated_at'] = date('Y-m-d H:i:s', time());
        }

        //创建合同明细更新数据
        $data['dataCont']['cont_handle_status'] = 110;
        //创建发票更新数据
        $data['dataInvoice']['invo_details_id'] = getId();
        $data['dataInvoice']['invo_id'] = 0;
        $data['dataInvoice']['invo_num'] = $input['invoice_num'];
        $data['dataInvoice']['invo_class'] = 'external';
        $data['dataInvoice']['invo_status'] = '401';
        $data['dataInvoice']['created_at'] = date('Y-m-d H:i:s', time());
        $data['dataInvoice']['updated_at'] = date('Y-m-d H:i:s', time());
        $data['dataInvoice']['invo_write_user'] = session('userInfo.user_id');
        $data['dataInvoice']['invo_write_date'] = date('Y-m-d H:i:s', time());
        //input数据
        $data['info']['invo_id'] = $input['invoInfo_id'];
        $data['info']['cont_details_id'] = $details_id;

        //签票事务处理
        $result = DB::transaction(function () use($data) {
            //添加开票数据
            InvoMainDb::insert($data['dataInvo']);
            //更新发票
            InvoiceDetailsDb::where('invo_details_id', $data['info']['invo_id'])
                ->insert($data['dataInvoice']);
            //更新合同明细
            ContDetailsDb::whereIn('details_id', $data['info']['cont_details_id'])
                ->update($data['dataCont']);
            //创建合同核销数据
            ContMainDb::insert($data['dataContMain']);

            return true;
        });

        if($result){
            return redirectPageMsg('1', "签票成功", route('invoCollect.index'));
        }else{
            return redirectPageMsg('-1', "签票失败", route('invoCollect.index'));
        }
    }
}
