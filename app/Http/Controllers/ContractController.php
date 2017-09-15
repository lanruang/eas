<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\Supplier\ContractModel AS ContractDb;
use App\Http\Models\System\SysAssemblyModel AS SysAssDb;
use Illuminate\Support\Facades\Input;
use Validator;

class ContractController extends Common\CommonController
{
    //合同列表
    public function index()
    {
        return view('contract.index');
    }

    //合同列表
    public function getCont(Request $request){
        //验证传输方式

        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = SupplierDb::count();
        //获取数据
        $result = SupplierDb::select('supp_id AS id', 'supp_num', 'supp_name')
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
            'contract_class' => 'required|between:1,8',
            'contract_type' => 'required|between:32,32',
            'contract_parties' => 'required|between:32,32',
            'contract_num' => 'required|between:0,150',
            'contract_name' => 'required|between:0,150',
            'contract_date' => 'required',
            'contract_amount' => 'required|numeric|min:0.01',
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('contract.createContract'));
        }
        //格式化合同期间
        $contract_date = $input['contract_date'];
        $contract_date = explode(' 一 ', $contract_date);
        if(count($contract_date) != 2){
            echoAjaxJson('-1', '合同期间错误！');
        }
        //合同信息
        $contData['cont_id'] = getId();
        $contData['cont_type'] = $input['contract_type'];
        $contData['cont_class'] = $input['contract_class'];
        $contData['cont_num'] = $input['contract_num'];
        $contData['cont_name'] = $input['contract_parties'];
        $contData['cont_start'] = $contract_date[0];
        $contData['cont_end'] = $contract_date[1];
        $contData['cont_status'] = '302';
        $contData['cont_sum_amount'] = $input['contract_amount'];
        $contData['cont_remark'] = $input['contract_remark'];
        //合同明细

        //合同附件
    p($input);


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
}
