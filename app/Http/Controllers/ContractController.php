<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\Supplier\ContractModel AS ContractDb;
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
        return view('contract.addContract');
    }

    //添加部门
    public function createSupplier()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'supp_num' => 'required|between:1,200',
            'supp_name' => 'required|between:1,200',
        ];
        $message = [
            'supp_num.required' => '请填写供应商编号',
            'supp_num.between' => '供应商编号字符数超出范围',
            'supp_name.required' => '请填写供应商名称',
            'supp_name.between' => '供应商名称字符数超出范围',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('supplier.addSupplier'));
        }

        //供应商是否存在
        $result = SupplierDb::where('supp_num', $input['supp_num'])
            ->orWhere('supp_name', $input['supp_name'])
            ->first();
        if($result){
            return redirectPageMsg('-1', "添加失败，供应商编号或供应商名称重复", route('supplier.addSupplier'));
        }

        //创建供应商
        $supplierDb = new SupplierDb();
        $supplierDb->supp_num = $input['supp_num'];
        $supplierDb->supp_name = $input['supp_name'];
        $result = $supplierDb->save();

        if($result){
            return redirectPageMsg('1', "添加成功", route('supplier.addSupplier'));
        }else{
            return redirectPageMsg('-1', "添加失败", route('supplier.addSupplier'));
        }
    }
    
    //编辑供应商视图
    public function editSupplier ()
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('supplier.index'));
        }
        $id = $input['id'];

        //获取供应商信息
        $supplier = SupplierDb::get()
            ->first()
            ->toArray();
        if(!$supplier){
            return redirectPageMsg('-1', "参数错误", route('supplier.index'));
        }

        return view('supplier.editSupplier', $supplier);
    }
    
    //更新供应商信息
    public function updateSupplier()
    {
        //验证表单
        $input = Input::all();
        //检测id类型是否整数
        if(!array_key_exists('supp_id', $input)){
            return redirectPageMsg('-1', '参数错误', route('supplier.index'));
        };

        $rules = [
            'supp_num' => 'required|between:1,200',
            'supp_name' => 'required|between:1,200',
        ];
        $message = [
            'supp_num.required' => '请填写供应商编号',
            'supp_num.between' => '供应商编号字符数超出范围',
            'supp_name.required' => '请填写供应商名称',
            'supp_name.between' => '供应商名称字符数超出范围',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('supplier.addSupplier'));
        }
        //供应商是否存在
        $result = SupplierDb::where('supp_id', $input['supp_id'])
                            ->first();
        if(!$result){
            return redirectPageMsg('-1', "修改失败，供应商不存在", route('supplier.index'));
        }
        //编号、名称是否存在
        $result = SupplierDb::where('supp_id','<>', $input['supp_id'])
            ->where(function ($query) use($input) {
                $query->where('supp_num', $input['supp_num'])
                    ->orWhere('supp_name', $input['supp_name']);
            })
            ->first();
        if($result){
            return redirectPageMsg('-1', "修改失败，供应商编号或供应商名称重复", route('supplier.index'));
        }

        //格式化数据
        $data['supp_num'] = $input['supp_num'];
        $data['supp_name'] = $input['supp_name'];

        //更新数据
        $result = SupplierDb::where('supp_id', $input['supp_id'])
            ->update($data);

        if($result){
            return redirectPageMsg('1', "编辑成功", route('supplier.index'));
        }else{
            return redirectPageMsg('-1', "编辑失败", route('supplier.editSupplier')."/".$input['supp_id']);
        }
    }

}
