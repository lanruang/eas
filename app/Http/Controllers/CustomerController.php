<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\Customer\CustomerModel AS CustomerDb;
use Illuminate\Support\Facades\Input;
use Validator;

class CustomerController extends Common\CommonController
{
    //客户列表
    public function index()
    {
        return view('customer.index');
    }

    //客户列表
    public function getCustomer(Request $request){
        //验证传输方式

        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }

        //获取记录总数
        $total = CustomerDb::count();
        //获取数据
        $result = CustomerDb::select('cust_id AS id', 'cust_num', 'cust_name')
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

    //添加客户视图
    public function addCustomer()
    {
        return view('customer.addCustomer');
    }

    //添加部门
    public function createCustomer()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'cust_num' => 'required|between:1,200',
            'cust_name' => 'required|between:1,200',
        ];
        $message = [
            'cust_num.required' => '请填写客户编号',
            'cust_num.between' => '客户编号字符数超出范围',
            'cust_name.required' => '请填写客户名称',
            'cust_name.between' => '客户名称字符数超出范围',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('customer.addCustomer'));
        }

        //客户是否存在
        $result = CustomerDb::where('cust_num', $input['cust_num'])
            ->orWhere('cust_name', $input['cust_name'])
            ->first();
        if($result){
            return redirectPageMsg('-1', "添加失败，客户编号或客户名称重复", route('customer.addCustomer'));
        }

        //创建客户
        $customerDb = new CustomerDb();
        $customerDb->cust_id= getId();
        $customerDb->cust_num = $input['cust_num'];
        $customerDb->cust_name = $input['cust_name'];
        $result = $customerDb->save();

        if($result){
            return redirectPageMsg('1', "添加成功", route('customer.addCustomer'));
        }else{
            return redirectPageMsg('-1', "添加失败", route('customer.addCustomer'));
        }
    }
    
    //编辑部门视图
    public function editCustomer()
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('customer.index'));
        }
        $id = $input['id'];

        //获取部门信息
        $customer = CustomerDb::where('cust_id', $id)
            ->get()
            ->first()
            ->toArray();
        if(!$customer){
            return redirectPageMsg('-1', "参数错误", route('customer.index'));
        }

        return view('customer.editCustomer', $customer);
    }
    
    //更新客户信息
    public function updateCustomer()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'cust_num' => 'required|between:1,200',
            'cust_name' => 'required|between:1,200',
            'cust_id' => 'required|between:32,32',
        ];
        $message = [
            'cust_num.required' => '请填写客户编号',
            'cust_num.between' => '客户编号字符数超出范围',
            'cust_name.required' => '请填写客户名称',
            'cust_name.between' => '客户名称字符数超出范围',
            'cust_id.required' => '参数不存在',
            'cust_id.integer' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('customer.addCustomer'));
        }
        //客户是否存在
        $result = CustomerDb::where('cust_id', $input['cust_id'])
                            ->first();
        if(!$result){
            return redirectPageMsg('-1', "修改失败，客户不存在", route('customer.index'));
        }
        //编号、名称是否存在
        $result = CustomerDb::where('cust_id','<>', $input['cust_id'])
            ->where(function ($query) use($input) {
                $query->where('cust_num', $input['cust_num'])
                    ->orWhere('cust_name', $input['cust_name']);
            })
            ->first();
        if($result){
            return redirectPageMsg('-1', "修改失败，客户编号或客户名称重复", route('customer.index'));
        }

        //格式化数据
        $data['cust_num'] = $input['cust_num'];
        $data['cust_name'] = $input['cust_name'];

        //更新数据
        $result = CustomerDb::where('cust_id', $input['cust_id'])
            ->update($data);

        if($result){
            return redirectPageMsg('1', "编辑成功", route('customer.index'));
        }else{
            return redirectPageMsg('-1', "编辑失败", route('customer.editCustomer')."?id=".$input['cust_id']);
        }
    }

}
