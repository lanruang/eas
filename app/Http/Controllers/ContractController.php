<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\Contract\ContractModel AS ContractDb;
use App\Http\Models\Contract\ContDetailsModel AS ContDetailsDb;
use App\Http\Models\Contract\ContEnclosureModel AS ContEncloDb;
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

class ContractController extends Common\CommonController
{
    //合同列表
    public function index()
    {
        //获取合同下拉菜单信息
        $data['select'] = SysAssDb::whereIn('ass_type', array('contract_class', 'contract_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('contract.index', $data);
    }

    //合同列表
    public function getContract(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //验证表单
        $input = Input::all();
        $searchSql = array();
        if(array_key_exists('contract_class', $input) && $input['contract_class']){
            $searchSql[] = array('cont.cont_class', $input['contract_class']);
        }
        if(array_key_exists('contract_type', $input) && $input['contract_type']){
            $searchSql[] = array('cont.cont_type', $input['contract_type']);
        }
        if(array_key_exists('contract_num', $input) && $input['contract_num']){
            $searchSql[] = array('cont.cont_num', 'like', '%' . $input['contract_num'] . '%');
        }
        if(array_key_exists('contract_name', $input) && $input['contract_name']){
            $searchSql[] = array('cont.cont_name', 'like', '%' . $input['contract_name'] . '%');
        }
        if(array_key_exists('supplier_name', $input) && $input['supplier_name']){
            $searchSql[] = array('supp.supp_name', 'like', '%' . $input['supplier_name'] . '%');
        }
        if(array_key_exists('customer_name', $input) && $input['customer_name']){
            $searchSql[] = array('cust.cust_name', 'like', '%' . $input['customer_name'] . '%');
        }
        if(array_key_exists('contract_status', $input) && $input['contract_status']){
            $searchSql[] = array('cont.cont_status', $input['contract_status']);
        }

        //获取记录总数
        $total = ContractDb::from('contract AS cont')
            ->leftJoin('sys_assembly AS sysAssType', 'cont.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'cont.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'cont.cont_parties','=','cust.cust_id')
            ->leftJoin('supplier AS supp', 'cont.cont_parties','=','supp.supp_id')
            ->where($searchSql)
            ->count();
        //获取数据
        $result = ContractDb::from('contract AS cont')
            ->leftJoin('sys_assembly AS sysAssType', 'cont.cont_type','=','sysAssType.ass_id')
            ->leftJoin('sys_assembly AS sysAssClass', 'cont.cont_class','=','sysAssClass.ass_id')
            ->leftJoin('customer AS cust', 'cont.cont_parties','=','cust.cust_id')
            ->leftJoin('supplier AS supp', 'cont.cont_parties','=','supp.supp_id')
            ->where($searchSql)
            ->select('cont.cont_id AS id', 'cont.cont_type AS contract_type', 'cont.cont_num AS contract_num',
                'cont.cont_name AS contract_name', 'cont.cont_start AS date_start', 'cont.cont_end AS date_end',
                'cont.cont_sum_amount AS contract_amount', 'cont.cont_status AS status', 'sysAssType.ass_text AS contract_type',
                'sysAssClass.ass_text AS contract_class')
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

    //添加合同视图
    public function addContract()
    {
        //获取合同下拉菜单信息
        $data['select'] = SysAssDb::whereIn('ass_type', array('contract_class', 'contract_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('contract.addContract', $data);
    }

    //添加合同
    public function createContract()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'contract_class' => 'required|between:32,32',
            'contract_type' => 'required|between:32,32',
            'contract_parties' => 'required|between:32,32',
            'contract_num' => 'required|between:0,150',
            'contract_name' => 'required|between:0,150',
            'contract_date' => 'required',
            'contract_amount' => 'required|numeric|min:0.01',
            'contract_subject' => 'required|between:32,32',
            'contract_dates' => 'required',
        ];
        $message = [
            'contract_class.required' => '请选择合同分组',
            'contract_class.between' => '合同分组数据错误',
            'contract_type.required' => '请选择合同类型',
            'contract_type.between' => '合同类型数据错误',
            'contract_parties.required' => '请选择合同方',
            'contract_parties.between' => '合同方数据错误',
            'contract_num.required' => '请填写合同编号',
            'contract_num.between' => '合同编号字符超出范围',
            'contract_name.required' => '请填写合同名称',
            'contract_name.between' => '合同名称字符超出范围',
            'contract_date.required' => '请选择合同期间',
            'contract_amount.required' => '请填写合同总金额',
            'contract_amount.numeric' => '合同总金额请输入数字',
            'contract_amount.min' => '合同总金额不能小于或者等于0',
            'contract_dates.required' => '请填写收付款期间',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('contract.addContract'));
        }
        //格式化合同期间
        $contract_date = $input['contract_date'];
        $contract_date = explode(' 一 ', $contract_date);
        if(count($contract_date) != 2){
            return redirectPageMsg('-1', '合同期间错误！', route('contract.addContract'));
        }
        //检查合同编号
        $isNum = ContractDb::where('cont_num', $input['contract_num'])
            ->first();
        if($isNum){
            return redirectPageMsg('-1', '合同编号存在！', route('contract.addContract'));
        }

        //合同信息
        $cont_id = getId();
        $contData['cont_id'] = $cont_id;
        $contData['cont_type'] = $input['contract_type'];
        $contData['cont_class'] = $input['contract_class'];
        $contData['cont_budget'] = session('userInfo.sysConfig.contract.budgetOnOff') == 1 ? $input['budget_id'] : '';
        $contData['cont_subject'] = $input['contract_subject'];
        $contData['cont_num'] = $input['contract_num'];
        $contData['cont_name'] = $input['contract_name'];
        $contData['cont_parties'] = $input['contract_parties'];
        $contData['cont_start'] = $contract_date[0];
        $contData['cont_end'] = $contract_date[1];
        $contData['cont_status'] = '302';
        $contData['cont_sum_amount'] = $input['contract_amount'];
        $contData['cont_remark'] = $input['contract_remark'];
        $contData['created_user'] = session('userInfo.user_id');
        $contData['created_at'] = date('Y-m-d H:i:s', time());
        $contData['updated_at'] = date('Y-m-d H:i:s', time());
        //合同明细
        $detailsData = explode('|', $input['contract_dates']);//分割日期
        foreach($detailsData as $k => $v){
            $a = explode(',', $v);  //分割数据
            if(count($a) != 2){
                return redirectPageMsg('-1', '合同明细期间参数错误！', route('contract.addContract'));
            }
            $contDetails[$k]['details_id'] = getId();
            $contDetails[$k]['cont_id'] = $cont_id;
            $contDetails[$k]['cont_details_date'] = $a[0];
            $contDetails[$k]['cont_amount'] = $a[1];
            $contDetails[$k]['cont_status'] = '302';
            $contDetails[$k]['cont_handle_status'] = '000';
            $contDetails[$k]['created_at'] = date('Y-m-d H:i:s', time());
            $contDetails[$k]['updated_at'] = date('Y-m-d H:i:s', time());
        }
        //合同附件
        $contEnclo = '';
        //移动单据文件
        if($input['enclosure']){
            $enclosures = explode('|', $input['enclosure']);
            foreach($enclosures as $k => $v){
                $fileName = explode(',', $v);
                if(count($fileName) != 2){
                    return redirectPageMsg('-1', '保存失败，附件名称格式化错误！', route('contract.addContract'));
                }
                $directory = 'contract/'.session('userInfo.user_id').'/'.$fileName[1];
                $exists = Storage::disk('storageTemp')->exists($directory);
                if(!$exists){
                    return redirectPageMsg('-1', '保存失败，附件获取失败，请刷新后重试！', route('contract.addContract'));
                }
                $oldFile = 'uploads/contract/'.session('userInfo.user_id').'/'.$fileName[1];
                $newFile = 'enclosure/contract/'.$cont_id.'/'.$fileName[1];
                $result = Storage::move($oldFile, $newFile);
                if(!$result){
                    return redirectPageMsg('-1', '保存失败，附件保存失败，请刷新后重试！', route('contract.addContract'));
                }
                $contEnclo[$k]['enclo_id'] = getId();
                $contEnclo[$k]['cont_id'] = $cont_id;
                $contEnclo[$k]['enclo_name'] = $fileName[0];
                $contEnclo[$k]['enclo_url'] = $newFile;
                $contEnclo[$k]['created_at'] = date('Y-m-d H:i:s', time());
                $contEnclo[$k]['updated_at'] = date('Y-m-d H:i:s', time());
            }
        }

        //事物创建数据
        $result = DB::transaction(function () use($contData, $contDetails, $contEnclo) {
            ContractDb::insert($contData);
            ContDetailsDb::insert($contDetails);
            if($contEnclo){
                ContEncloDb::insert($contEnclo);
            }
            return true;
        });

        if($result){
            return redirectPageMsg('1', "提交成功", route('contract.index'));
        }else{
            return redirectPageMsg('-1', "提交失败", route('contract.addContract'));
        }
    }

    //编辑合同视图
    public function editContract()
    {
        //获取参数
        $input = Input::all();
        //过滤信息
        $rules = [
            'id' => 'required|between:32,32',
        ];
        $message = [
            'id.required' => '参数不存在',
            'id.integer' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('contract.index'));
        }
        $id = $input['id'];

        //查询合同是否存在
        $data['contract'] = ContractDb::from('contract AS cont')
            ->leftJoin('subjects AS sub', 'cont.cont_subject','=','sub.sub_id')
            ->where('cont.cont_id', $id)
            ->select('cont.*', 'sub.sub_ip', 'sub.sub_name', 'sub.sub_pid')
            ->first()
            ->toArray();

        if(!$data['contract']){
            return redirectPageMsg('-1', '合同信息获取失败，请刷新后重试', route('contract.index'));
        }
        $data['contract']['cont_sum_amount'] = sprintf("%.2f",$data['contract']['cont_sum_amount']);

        //获取合同方信息
        if($data['contract']['cont_class'] == session('userInfo.sysConfig.contract.income')){
                $parties = CustomerDb::where('cust_id', $data['contract']['cont_parties'])
                    ->select('cust_name')
                    ->first()
                    ->toArray();
            }else{
                $parties = SupplierDb::where('supp_id', $data['contract']['cont_parties'])
                    ->select('supp_name')
                    ->first()
                    ->toArray();
            }
        if(!$parties){
            return redirectPageMsg('-1', '合同方信息获取失败，请刷新后重试', route('contract.index'));
        }
        $data['contract']['parties_name'] = $parties['cust_name'];

        //获取上级科目
        $subject = SubjectsDb::where('sub_id', $data['contract']['sub_pid'])
            ->select('sub_name')
            ->get()
            ->toArray();
        if($subject){
            $data['contract']['sub_name'] = $subject[0]['sub_name'] ." - ".$data['contract']['sub_name'];
        }

        //获取收费期间
        $data['contDetails'] = ContDetailsDb::where('cont_id', $data['contract']['cont_id'])
            ->orderBy('cont_details_date')
            ->get()
            ->toArray();

        //获取附件
        $data['contEnclo'] = ContEncloDb::where('cont_id', $data['contract']['cont_id'])
            ->select('enclo_id', 'enclo_name')
            ->get()
            ->toArray();

        //获取合同下拉菜单信息
        $data['select'] = SysAssDb::whereIn('ass_type', array('contract_class', 'contract_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('contract.editContract', $data);
    }

    //更新合同
    public function updateContract()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'contract_class' => 'required|between:32,32',
            'contract_type' => 'required|between:32,32',
            'contract_parties' => 'required|between:32,32',
            'contract_num' => 'required|between:0,150',
            'contract_name' => 'required|between:0,150',
            'contract_date' => 'required',
            'contract_amount' => 'required|numeric|min:0.01',
            'contract_subject' => 'required|between:32,32',
            'contract_id' => 'required|between:32,32',
        ];
        $message = [
            'contract_class.required' => '请选择合同分组',
            'contract_class.between' => '合同分组数据错误',
            'contract_type.required' => '请选择合同类型',
            'contract_type.between' => '合同类型数据错误',
            'contract_parties.required' => '请选择合同方',
            'contract_parties.between' => '合同方数据错误',
            'contract_num.required' => '请填写合同编号',
            'contract_num.between' => '合同编号字符超出范围',
            'contract_name.required' => '请填写合同名称',
            'contract_name.between' => '合同名称字符超出范围',
            'contract_date.required' => '请选择合同期间',
            'contract_amount.required' => '请填写合同总金额',
            'contract_amount.numeric' => '合同总金额请输入数字',
            'contract_amount.min' => '合同总金额不能小于或者等于0',
            'contract_id.required' => '缺少参数',
            'contract_id.between' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('contract.editContract')."?id=".$input['contract_id']);
        }
        //查询合同是否存在
        $contract = ContractDb::where('cont_id', $input['contract_id'])
            ->first();
        if(!$contract){
            return redirectPageMsg('-1', '合同不存在！', route('contract.editContract')."?id=".$input['contract_id']);
        }

        if($contract['cont_num'] != $input['contract_num']){
            //检查合同编号
            $isNum = ContractDb::where('cont_num', $input['contract_num'])
                ->first();
            if($isNum){
                return redirectPageMsg('-1', '合同编号存在！', route('contract.editContract')."?id=".$input['contract_id']);
            }
        }

        //格式化合同期间
        $contract_date = $input['contract_date'];
        $contract_date = explode(' 一 ', $contract_date);
        if(count($contract_date) != 2){
            return redirectPageMsg('-1', '合同期间错误！', route('contract.editContract')."?id=".$input['contract_id']);
        }

        //合同信息
        $contData['cont_type'] = $input['contract_type'];
        $contData['cont_class'] = $input['contract_class'];
        $contData['cont_budget'] = session('userInfo.sysConfig.contract.budgetOnOff') == 1 ? $input['budget_id'] : '';
        $contData['cont_subject'] = $input['contract_subject'];
        $contData['cont_num'] = $input['contract_num'];
        $contData['cont_name'] = $input['contract_name'];
        $contData['cont_parties'] = $input['contract_parties'];
        $contData['cont_start'] = $contract_date[0];
        $contData['cont_end'] = $contract_date[1];
        $contData['cont_status'] = '302';
        $contData['cont_sum_amount'] = $input['contract_amount'];
        $contData['cont_remark'] = $input['contract_remark'];
        $contData['created_user'] = session('userInfo.user_id');
        $contData['created_at'] = date('Y-m-d H:i:s', time());
        $contData['updated_at'] = date('Y-m-d H:i:s', time());
        $contDetails = '';
        //合同明细
        if($input['contract_dates']){
            $contDetails = array();
            $detailsData = explode('|', $input['contract_dates']);//分割日期
            foreach($detailsData as $k => $v){
                $a = explode(',', $v);  //分割数据
                if(count($a) != 2){
                    return redirectPageMsg('-1', '合同明细期间参数错误！', route('contract.editContract')."?id=".$input['contract_id']);
                }
                $contDetails[$k]['details_id'] = getId();
                $contDetails[$k]['cont_id'] = $contract['cont_id'];
                $contDetails[$k]['cont_details_date'] = $a[0];
                $contDetails[$k]['cont_amount'] = $a[1];
                $contDetails[$k]['cont_status'] = '302';
                $contDetails[$k]['cont_handle_status'] = '000';
                $contDetails[$k]['created_at'] = date('Y-m-d H:i:s', time());
                $contDetails[$k]['updated_at'] = date('Y-m-d H:i:s', time());
            }
        }

        //合同附件
        $contEnclo = '';
        //移动单据文件
        if($input['enclosure']){
            $enclosures = explode('|', $input['enclosure']);
            foreach($enclosures as $k => $v){
                $fileName = explode(',', $v);
                if(count($fileName) != 2){
                    return redirectPageMsg('-1', '保存失败，附件名称格式化错误！', route('contract.editContract')."?id=".$input['contract_id']);
                }
                $directory = 'contract/'.session('userInfo.user_id').'/'.$fileName[1];
                $exists = Storage::disk('storageTemp')->exists($directory);
                if(!$exists){
                    return redirectPageMsg('-1', '保存失败，附件获取失败，请刷新后重试！', route('contract.editContract')."?id=".$input['contract_id']);
                }
                $oldFile = 'uploads/contract/'.session('userInfo.user_id').'/'.$fileName[1];
                $newFile = 'enclosure/contract/'.$contract['cont_id'].'/'.$fileName[1];
                $result = Storage::move($oldFile, $newFile);
                if(!$result){
                    return redirectPageMsg('-1', '保存失败，附件保存失败，请刷新后重试！', route('contract.editContract')."?id=".$input['contract_id']);
                }
                $contEnclo[$k]['enclo_id'] = getId();
                $contEnclo[$k]['cont_id'] = $contract['cont_id'];
                $contEnclo[$k]['enclo_name'] = $fileName[0];
                $contEnclo[$k]['enclo_url'] = $newFile;
                $contEnclo[$k]['created_at'] = date('Y-m-d H:i:s', time());
                $contEnclo[$k]['updated_at'] = date('Y-m-d H:i:s', time());
            }
        }

        //事物创建数据
        $result = DB::transaction(function () use($contract, $contData, $contDetails, $contEnclo) {
            ContractDb::where('cont_id', $contract['cont_id'])
            ->update($contData);
            if($contDetails){
                ContDetailsDb::insert($contDetails);
            }
            if($contEnclo){
                ContEncloDb::insert($contEnclo);
            }
            return true;
        });

        if($result){
            return redirectPageMsg('1', "提交成功", route('contract.index'));
        }else{
            return redirectPageMsg('-1', "提交失败", route('contract.editContract')."?id=".$input['contract_id']);
        }
    }

    //删除合同
    public function delContract(Request $request)
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
        $id = $input['id'];

        //获取单据信息
        $contract = ContractDb::where('cont_id', $id)
            ->get()
            ->first();
        if (!$contract) echoAjaxJson('-1', "删除失败，合同信息获取失败");
        if ($contract['cont_status'] != '302') echoAjaxJson('-1', "删除失败，合同状态不正确");

        //事物删除数据
        $result = DB::transaction(function () use($id) {
            ContractDb::where('cont_id', $id)
                ->delete();
            ContDetailsDb::where('cont_id', $id)
                ->delete();
            ContEncloDb::where('cont_id', $id)
                ->delete();
            return true;
        });

        //删除上传的图片
        $contractDir = 'contract/'.$id;
        $uploadsDir = 'contract/'.$contract['created_user'];
        Storage::disk('storageTemp')->deleteDirectory($uploadsDir);
        Storage::disk('storage')->deleteDirectory($contractDir);
        if($result){
            echoAjaxJson('1', "删除成功");
        }else{
            echoAjaxJson('-1', "删除失败，请刷新后重试");
        }
    }

    //删除合同收付期间
    public function delDetails(Request $request)
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
        $id = $input['id'];

        //查询附件是否存在
        $enclo = ContDetailsDb::where('details_id', $id)
            ->get()
            ->toArray();
        if(!$enclo){
            echoAjaxJson('-1', "删除失败，期间不存在！");
        }
        //删除附件
        $result = ContDetailsDb::where('details_id', $id)
            ->delete();
        if($result){
            echoAjaxJson('1', "删除成功");
        }else{
            echoAjaxJson('-1', "删除失败，请刷新后重试！");
        }
    }

    //删除合同附件
    public function delEnclo(Request $request)
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
        $id = $input['id'];

        //查询附件是否存在
        $enclo = ContEncloDb::where('enclo_id', $id)
            ->get()
            ->toArray();
        if(!$enclo){
            echoAjaxJson('-1', "删除失败，附件不存在！");
        }
        //删除附件
        $result = ContEncloDb::where('enclo_id', $id)
            ->delete();

        $contDir = substr($enclo->enclo_url ,10 ,strlen($enclo->enclo_url));
        Storage::disk('storage')->delete($contDir);

        if($result){
            echoAjaxJson('1', "删除成功");
        }else{
            echoAjaxJson('-1', "删除失败，请刷新后重试！");
        }
    }

    //获取预算科目
    public function getBudgetSub(Request $request)
    {
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //开启报销预算
        if(session('userInfo.sysConfig.contract.budgetOnOff') == 1){
            //获取参数
            $input = Input::all();
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

            $subjects = SubjectsDb::leftjoin('budget_subject AS bs', function ($join) use ($input) {
                $join->on('bs.subject_id', '=', 'subjects.sub_id')
                    ->where('bs.budget_id', $input['id']);
            })
                ->where('subjects.status', 1)
                ->select('subjects.sub_id AS id', 'subjects.sub_ip AS sub_ip', 'subjects.sub_pid AS pid',
                    'subjects.sub_name AS text', 'bs.status AS status')
                ->orderBy('subjects.sub_ip', 'ASC')
                ->get()
                ->toArray();
        }else{
            $subjects = SubjectsDb::where('status', 1)
                ->select('sub_id AS id', 'sub_ip', 'sub_pid AS pid', 'sub_name AS text', 'status')
                ->orderBy('sub_ip', 'ASC')
                ->get()
                ->toArray();
        }

        //树形排列科目
        $result = getTree($subjects, session('userInfo.sysConfig.contract.subContract'));

        //创建结果数据
        $data['data'] = $result;
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }

    //上传附件
    public function uploadEnclo(Request $request){
        $file = $request->file('file');
        $ext_mime = ["image/jpg", "image/jpeg", "image/png", "image/gif", "image/bmp"];//扩展名

        if (!$file->isValid()) {
            echoAjaxJson('-1', '上传失败，请刷新后重试！');
        }
        // 获取文件相关信息
        //$originalName = $file->getClientOriginalName();               // 文件原名
        $ext = $file->getClientOriginalExtension();                     // 扩展名
        //$realPath = $file->getRealPath();                               //临时文件的绝对路径
        $type = $file->getClientMimeType();                             //mime
        $size = $file->getClientSize()/1000/1000;                       //获取文件尺寸MB
        $fileName = date('Y-m-d-H-i-s'). '-' . uniqid() . '.' .$ext;    // 上传文件名
        if(!in_array($type, $ext_mime)){
            echoAjaxJson('-1', '上传失败，只能上传图片（jpg、jpeg、png、gif、bmp）！');
        }
        if($size > 5){
            return echoAjaxJson('-1', '上传失败，图片过大，最大支持5MB！');
        }
        $filePath = 'uploads/contract/'.session('userInfo.user_id');
        $rel = $file->move($filePath ,$fileName);

        //$rel = Storage::disk('storageTemp')->put($filePath, file_get_contents($realPath));
        if(!$rel){
            echoAjaxJson('-1', '上传失败，请刷新后重试！');
        }
        $url = asset('uploads/contract/'.session('userInfo.user_id')).'/'.$fileName;
        $data['fUrl'] = $url;
        $data['url'] = $fileName;
        echoAjaxJson('1', '上传成功', $data);
    }

    //查看合同详情
    public function listContract()
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
        if ($validator->fails()) {
            return redirectPageMsg('-1', $validator->errors()->first(), route('contract.index'));
        }
        //合同信息
        $data['contract'] = ContractDb::from('contract AS cont')
                ->leftJoin('sys_assembly AS sysAssType', 'cont.cont_type','=','sysAssType.ass_id')
                ->leftJoin('sys_assembly AS sysAssClass', 'cont.cont_class','=','sysAssClass.ass_id')
                ->leftJoin('budget AS budget', 'cont.cont_budget','=','budget.budget_name')
                ->leftJoin('subjects AS sub', 'cont.cont_subject','=','sub.sub_id')
                ->select('cont.cont_id AS id',
                    'cont.cont_type AS contract_type',
                    'cont.cont_num AS contract_num',
                    'cont.cont_name AS contract_name',
                    'cont.cont_start AS date_start',
                    'cont.cont_end AS date_end',
                    'cont.cont_sum_amount AS contract_amount',
                    'cont.cont_status AS status',
                    'cont.cont_class AS cont_class',
                    'sysAssType.ass_text AS contract_type',
                    'sysAssClass.ass_text AS contract_class',
                    'cont.cont_status AS status',
                    'budget.budget_name',
                    'sub.sub_name',
                    'cont.cont_remark',
                    'cont.cont_parties')
                ->where('cont.cont_id', $input['id'])
                ->first();
        if(!$data['contract']){
            return redirectPageMsg('-1', '合同不存在,请刷新后重试', route('contract.index'));
        }
        //合同期间
        $data['contDetails'] = ContDetailsDb::where('cont_id', $data['contract']->id)
            ->orderBy('cont_details_date', 'ASC')
            ->get()
            ->toArray();
        if(!$data['contDetails']){
            return redirectPageMsg('-1', '合同期间获取失败,请刷新后重试', route('contract.index'));
        }
        //合同附件
        $data['contEnclo'] = ContEncloDb::where('cont_id', $data['contract']->id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->toArray();
        //客户信息
        $parties = '';
        if($data['contract']->cont_class == session('userInfo.sysConfig.contract.income')){
            $parties = CustomerDb::where('cust_id', $data['contract']->cont_parties)
                ->first();
        }
        if($data['contract']->cont_class == session('userInfo.sysConfig.contract.payment')){
            $parties = SupplierDb::where('supp_id', $data['contract']->cont_parties)
                ->first();
        }
        if(!$parties){
            return redirectPageMsg('-1', '获取合同方失败', route('contract.index'));
        }
        $data['contract']['parties'] = $parties['cust_name'];

        return view('contract.listContract', $data);
    }

    //提交审批
    public function addAudit(Request $request)
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
        $id = $input['id'];

        $contract = ContractDb::where('cont_id', $id)
            ->first();
        if(!$contract) echoAjaxJson('-1', '参数错误，合同不存！');
        if($contract['cont_status'] != '302') echoAjaxJson('-1', '提交失败，单据状态错误！');

        //获取预算审批流程
        $whereIn[] = 0;
        $whereIn[] = session('userInfo.dep_id');
        $auditArr = AuditProcessDb::where('audit_type', 'contract')
            ->whereIn('audit_dep', $whereIn)
            ->get()
            ->toArray();

        //删除临时上传的图片
        $directory = 'contract/'.$id;
        Storage::disk('storageTemp')->deleteDirectory($directory);

        if($auditArr){
            //审批流程大于1则获取对应部门预算
            if(count($auditArr) > 1){
                foreach($auditArr as $k => $v){
                    if($v['audit_dep'] == session('userInfo.dep_id')){
                        $auditArr = $v;
                    }
                }
            }else{
                $auditArr = $auditArr[0];
            }

            if(!$auditArr['audit_process']) echoAjaxJson('-1', '提交失败，审批流程人员获取失败！');

            $result = DB::transaction(function () use($id, $contract, $input, $auditArr) {
                $process_users = explode(',', $auditArr['audit_process']);
                //审批内容参数
                $auditInfoDb = new AuditInfoDb();
                $auditInfoDb->process_id = getId();
                $auditInfoDb->process_type = 'contract';
                $auditInfoDb->process_app = $contract['cont_id'];
                $auditInfoDb->process_title = '合同编号：'.$contract['cont_num'];
                $auditInfoDb->process_text = $input['process_text'];
                $auditInfoDb->process_users = $auditArr['audit_process'];
                $auditInfoDb->process_audit_user = $process_users[0];
                $auditInfoDb->created_user = session('userInfo.user_id');
                $auditInfoDb->status = '1000';
                $auditInfoDb->save();

                //更新合同状态
                ContractDb::where('cont_id', $id)
                    ->update(array('cont_status'=>1009));
                //更新合同状态
                ContDetailsDb::where('cont_id', $id)
                    ->update(array('cont_status'=>1009));
                return true;
            });

            if($result){
                echoAjaxJson('1', '提交成功，请耐心等待审批！');
            }else{
                echoAjaxJson('-1', '审批失败，请重新提交！');
            }
        }else{
            $result = DB::transaction(function () use($id) {
                //转换状态
                $data['cont_status'] = '301';
                //更新单据状态
                $result = ContractDb::where('cont_id', $id)
                    ->where('cont_status', '302')
                    ->update($data);
                if(!$result){
                    return false;
                }
                //获取单据状态
                $contract = ContractDb::where('cont_id', $id)
                    ->select('cont_num')
                    ->first();

                //发送通知
                $notice['notice_id'] = getId();
                $notice['notice_app'] = $id;//需要确认操作
                $notice['notice_message'] = '合同编号：'.$contract['cont_num'].'。已通过审批。';
                $notice['notice_user'] = session('userInfo.user_id');
                $this->createNotice($notice);
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
        $id = $input['id'];
        //获取编辑状态单据
        $contract = ContractDb::where('cont_id', $id)
            ->first();
        if(!$contract){
            echoAjaxJson('-1', '合同信息获取失败，请刷新后重试!');
        }

        //获取审批流程信息
        $audit = AuditInfoDb::where('process_type', 'contract')
            ->where('process_app', $id)
            ->select('process_audit_user', 'process_users', 'process_id', 'status')
            ->first();
        if(!$audit){
            echoAjaxJson('-1', '该项目未提交审批!');
        }

        //格式化流程
        $data['audit_user'] = $audit['process_audit_user'];
        $auditUser = explode(',', $audit['process_users']);
        $data['auditProcess'] = array();

        //审核流程
        $result = UserDb::leftjoin('users_base AS ub', 'users.user_id', '=', 'ub.user_id')
            ->leftjoin('department AS dep', 'ub.department', '=', 'dep.dep_id')
            ->leftjoin('positions AS pos', 'ub.positions', '=', 'pos.pos_id')
            ->select('dep.dep_name', 'pos.pos_name', 'users.user_name', 'users.user_id AS uid')
            ->whereIn('users.user_id', $auditUser)
            ->get()
            ->toArray();
        //获取审核信息
        $auditInfoText = AuditInfoTextDb::where('process_id', $audit['process_id'])
            ->select('audit_text', 'audit_res', 'created_at AS audit_time', 'created_user')
            ->get()
            ->toArray();

        foreach($auditUser as $k => $v){
            foreach($result as $rv){
                if($v == $rv['uid']){
                    $data['auditProcess'][$k] = $rv;
                }
            }
            foreach($auditInfoText as $av){
                if($v == $av['created_user']){
                    $data['auditProcess'][$k] = array_merge($data['auditProcess'][$k], $av);
                }
            }
        }
        
        //格式化数据
        $data['audit_status'] = $audit['status'];
        $data['status'] = 1;

        //返回结果
        ajaxJsonRes($data);
    }
}
