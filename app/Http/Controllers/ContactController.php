<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\Contact\ContactModel AS ContactDb;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\Customer\CustomerModel AS CustomerDb;
use App\Http\Models\Supplier\SupplierModel AS SupplierDb;

class ContactController extends Common\CommonController
{
    //联系人列表
    public function index()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'type' => 'required',
            'partie' => 'required|between:32,32'
        ];
        $message = [
            'type.required' => '缺少参数',
            'partie.required' => '缺少参数',
            'partie.between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('main.index'));
        }
        $info['type'] = $input['type'];
        $info['partie'] = $input['partie'];
        $info['partie_name'] = $input['type'] == 'customer' ? '客户详情' : '供应商详情';
        $info['partie_url'] = $input['type'] == 'customer' ? route('customer.listCustomer', ['id' => $input['partie']]) : route('supplier.listSupplier', ['id' => $input['partie']]);

        return view('contact.index', $info);
    }

    //联系人列表
    public function getContact(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }
        //验证表单
        $input = Input::all();
        $rules = [
            'type' => 'required',
            'partie' => 'required|between:32,32'
        ];
        $message = [
            'type.required' => '缺少参数',
            'partie.required' => '缺少参数',
            'partie.between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson('-1', $validator->errors()->first());
        }
        //获取记录总数
        $total = ContactDb::count();
        //获取数据
        $result = ContactDb::where('cont_partie_type', $input['type'])
            ->where('cont_partie', $input['partie'])
            ->select('cont_id AS id', 'cont_name AS contact_name', 'cont_eName AS contact_eName', 'cont_phone AS contact_phone',
            'cont_mPhone AS contact_mPhone', 'cont_email AS contact_email', 'cont_address AS contact_address',
            'cont_birthday AS contact_birthday', 'cont_remark AS contact_remark')
            ->orderBy('created_at', 'ASC')
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

    //添加联系人视图
    public function addContact()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'type' => 'required',
            'partie' => 'required|between:32,32'
        ];
        $message = [
            'type.required' => '缺少参数',
            'partie.required' => '缺少参数',
            'partie.between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('main.index'));
        }
        $info['type'] = $input['type'];
        $info['partie'] = $input['partie'];
        $info['partie_name'] = $input['type'] == 'customer' ? '客户详情' : '供应商详情';
        $info['partie_url'] = $input['type'] == 'customer' ? route('customer.listCustomer', ['id' => $input['partie']]) : route('supplier.listSupplier', ['id' => $input['partie']]);
        $info['url'] = route('contact.index', ['type' => $input['type'], 'partie' => $input['partie']]);

        return view('contact.addContact', $info);
    }

    //添加联系人
    public function createContact()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'contact_name' => 'required|max:30',
            'contact_eName' => 'max:30',
            'contact_phone' => 'max:30',
            'contact_mPhone' => 'max:30',
            'contact_email' => 'max:30',
            'contact_address' => 'max:30',
            'contact_birthday' => 'required',
            'contact_type' => 'required|max:10',
            'contact_partie' => 'required|between:32, 32'
        ];
        $message = [
            'contact_name.required' => '联系人名称未填写',
            'contact_name.max' => '联系人名称字符数过多',
            'contact_eName.max' => '排序未填写',
            'contact_phone.max' => '参数错误',
            'contact_mPhone.max' => '参数错误',
            'contact_email.max' => '参数错误',
            'contact_address.max' => '参数错误',
            'contact_birthday.required' => '请选择生日日期',
            'contact_type.required' => '缺少参数',
            'contact_type.max' => '参数错误',
            'contact_partie.required' => '缺少参数',
            'contact_partie.between' => '参数错误'
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('contact.addContact', ['type' => $input['contact_type'], 'partie' => $input['contact_partie']]));
        }

        //查询联系人归属是否存在
        $partie = '';
        if($input['contact_type'] == 'customer'){
            $partie = CustomerDb::where('cust_id', $input['contact_partie'])
                ->first();
        }else{
            $partie = SupplierDb::where('supplier_id', $input['contact_partie'])
                ->first();
        }
        if(!$partie){
            return redirectPageMsg('-1', $validator->errors()->first(), route('main.index'));
        }
        //创建员工
        $contact = new ContactDb();
        $contact->cont_id = getId();
        $contact->cont_partie_type = $input['contact_type'];
        $contact->cont_partie = $input['contact_partie'];
        $contact->cont_name = $input['contact_name'];
        $contact->cont_name = $input['contact_name'];
        $contact->cont_eName = $input['contact_eName'];
        $contact->cont_phone = $input['contact_phone'];
        $contact->cont_mPhone = $input['contact_mPhone'];
        $contact->cont_email = $input['contact_email'];
        $contact->cont_address = $input['contact_address'];
        $contact->cont_birthday = $input['contact_birthday'];
        $contact->cont_remark = $input['contact_remark'];
        $contact->updated_at = date('Y-m-d H:i:s', time());
        $contact->created_at = date('Y-m-d H:i:s', time());
        $result = $contact->save();

        if($result){
            return redirectPageMsg('1', "添加成功", route('contact.index', ['type' => $input['contact_type'], 'partie' => $input['contact_partie']]));
        }else{
            return redirectPageMsg('-1', "添加失败", route('contact.addContact', ['type' => $input['contact_type'], 'partie' => $input['contact_partie']]));
        }
    }
    
    //编辑联系人视图
    public function editContact()
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('main.index'));
        }
        $id = $input['id'];

        //获取联系人信息
        $data = ContactDb::select('cont_id AS contact_id', 'cont_name AS contact_name', 'cont_eName AS contact_eName', 'cont_phone AS contact_phone',
            'cont_mPhone AS contact_mPhone', 'cont_email AS contact_email', 'cont_address AS contact_address',
            'cont_birthday AS contact_birthday', 'cont_remark AS contact_remark', 'cont_partie_type', 'cont_partie')
            ->where('cont_id', $id)
            ->first();
        if(!$data){
            return redirectPageMsg('-1', "联系人不存在", route('main.index'));
        }
        $data = $data->toArray();

        $data['info']['type'] = $data['cont_partie_type'];
        $data['info']['partie'] = $data['cont_partie'];
        $data['info']['partie_name'] = $data['cont_partie_type'] == 'customer' ? '客户详情' : '供应商详情';
        $data['info']['partie_url'] = $data['cont_partie_type'] == 'customer' ? route('customer.listCustomer', ['id' => $data['cont_partie']]) : route('supplier.listSupplier', ['id' => $data['cont_partie']]);
        $data['info']['url'] = route('contact.index', ['type' => $data['cont_partie_type'], 'partie' => $data['cont_partie']]);

        return view('contact.editContact', $data);
    }
    
    //更新联系人信息
    public function updateContact()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'contact_name' => 'required|max:30',
            'contact_eName' => 'max:30',
            'contact_phone' => 'max:30',
            'contact_mPhone' => 'max:30',
            'contact_email' => 'max:30',
            'contact_address' => 'max:30',
            'contact_birthday' => 'required',
            'contact_id' => 'required|between:32,32',
        ];
        $message = [
            'contact_name.required' => '联系人名称未填写',
            'contact_name.max' => '联系人名称字符数过多',
            'contact_eName.max' => '排序未填写',
            'contact_phone.max' => '参数错误',
            'contact_mPhone.max' => '参数错误',
            'contact_email.max' => '参数错误',
            'contact_address.max' => '参数错误',
            'contact_birthday.required' => '请选择生日日期',
            'contact_id.required' => '参数不存在',
            'contact_id.between' => '参数错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('contact.editContact')."?id=".$input['contact_id']);
        }

        //获取联系人信息
        $contact = ContactDb::select('cont_id AS contact_id', 'cont_name AS contact_name', 'cont_eName AS contact_eName', 'cont_phone AS contact_phone',
            'cont_mPhone AS contact_mPhone', 'cont_email AS contact_email', 'cont_address AS contact_address',
            'cont_birthday AS contact_birthday', 'cont_remark AS contact_remark', 'cont_partie_type', 'cont_partie')
            ->where('cont_id', $input['contact_id'])
            ->first();
        if(!$contact){
            return redirectPageMsg('-1', "联系人不存在", route('main.index'));
        }

        //格式化数据
        $data['cont_name'] = $input['contact_name'];
        $data['cont_eName'] = $input['contact_eName'];
        $data['cont_phone'] = $input['contact_phone'];
        $data['cont_mPhone'] = $input['contact_mPhone'];
        $data['cont_email'] = $input['contact_email'];
        $data['cont_address'] = $input['contact_address'];
        $data['cont_birthday'] = $input['contact_birthday'];
        $data['cont_remark'] = $input['contact_remark'];

        //更新数据
        $result = ContactDb::where('cont_id', $input['contact_id'])
            ->update($data);

        if($result){
            return redirectPageMsg('1', "编辑成功", route('contact.index', ['type' => $contact['cont_partie_type'], 'partie' => $contact['cont_partie']]));
        }else{
            return redirectPageMsg('-1', "编辑失败", route('contact.editContact', ['id' => $input['contact_id']]));
        }
    }

}
