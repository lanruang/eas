<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\Customer\CustomerModel AS CustomerDb;
use Illuminate\Support\Facades\Input;
use Validator;
use Storage;
use App\Http\Models\System\SysAssemblyModel AS SysAssDb;
use App\Http\Models\Customer\CustEnclosureModel AS CustEncloDb;
use Illuminate\Support\Facades\DB;

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
        //获取下拉菜单信息
        $data['select'] = SysAssDb::whereIn('ass_type', array('customer_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('customer.addCustomer', $data);
    }

    //添加客户
    public function createCustomer()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'customer_type' => 'required|between:32,32',
            'customer_num' => 'required|between:1,200',
            'customer_name' => 'required|between:1,200',
            'customer_phone' => 'max:18',
            'customer_fax' => 'max:40',
            'customer_address' => 'max:180',
            'customer_website' => 'max:180',
            'customer_tax_num' => 'max:28',
            'customer_join_time' => 'required|date',
            'customer_end_time' => 'required|date',
        ];
        $message = [
            'customer_type.required' => '请填写选择客户类别',
            'customer_type.between' => '客户类别参数错误',
            'customer_num.required' => '请填写客户编号',
            'customer_num.between' => '客户编号字符数超出范围',
            'customer_name.required' => '请填写客户名称',
            'customer_name.between' => '客户名称字符数超出范围',
            'customer_phone.max' => '联系电话字符数超出范围',
            'customer_fax.max' => '传真字符数超出范围',
            'customer_address.max' => '地址字符数超出范围',
            'customer_website.max' => '网站字符数超出范围',
            'customer_tax_num.max' => '税号字符数超出范围',
            'customer_join_time.required' => '请选择加入日期',
            'customer_join_time.date' => '加入时间格式错误',
            'customer_end_time.required' => '请选择撤出日期',
            'customer_end_time.date' => '撤出日期格式错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('customer.addCustomer'));
        }

        //客户是否存在
        $result = CustomerDb::where('cust_num', $input['customer_num'])
            ->orWhere('cust_name', $input['customer_name'])
            ->first();
        if($result){
            return redirectPageMsg('-1', "添加失败，客户编号或客户名称重复", route('customer.addCustomer'));
        }

        $cust_id = getId();

        //客户附件
        $custEnclo = array();
        //移动单据文件
        if($input['enclosure']){
            $enclosures = explode('|', $input['enclosure']);
            foreach($enclosures as $k => $v){
                $fileName = explode(',', $v);
                if(count($fileName) != 2){
                    return redirectPageMsg('-1', '保存失败，附件名称格式化错误！', route('customer.addCustomer'));
                }
                $directory = 'customer/'.session('userInfo.user_id').'/'.$fileName[1];
                $exists = Storage::disk('storageTemp')->exists($directory);
                if(!$exists){
                    $contractDir = 'customer/'.$cust_id;
                    Storage::disk('storage')->deleteDirectory($contractDir);
                    return redirectPageMsg('-1', '保存失败，附件获取失败，请刷新后重试！', route('customer.addCustomer'));
                }
                $oldFile = 'uploads/customer/'.session('userInfo.user_id').'/'.$fileName[1];
                $newFile = 'enclosure/customer/'.$cust_id.'/'.$fileName[1];
                $result = Storage::move($oldFile, $newFile);
                if(!$result){
                    $contractDir = 'customer/'.$cust_id;
                    Storage::disk('storage')->deleteDirectory($contractDir);
                    return redirectPageMsg('-1', '保存失败，附件保存失败，请刷新后重试！', route('customer.addCustomer'));
                }

                $custEnclo[$k]['enclo_id'] = getId();
                $custEnclo[$k]['cust_id'] = $cust_id;
                $custEnclo[$k]['enclo_name'] = $fileName[0];
                $custEnclo[$k]['enclo_url'] = $newFile;
                $custEnclo[$k]['created_at'] = date('Y-m-d H:i:s', time());
                $custEnclo[$k]['updated_at'] = date('Y-m-d H:i:s', time());
            }
        }

        //创建客户
        $data['cust_id'] = $cust_id;
        $data['cust_type'] = $input['customer_type'];
        $data['cust_num'] = $input['customer_num'];
        $data['cust_name'] = $input['customer_name'];
        $data['cust_phone'] = $input['customer_phone'];
        $data['cust_fax'] = $input['customer_fax'];
        $data['cust_address'] = $input['customer_address'];
        $data['cust_website'] = $input['customer_website'];
        $data['cust_tax_num'] = $input['customer_tax_num'];
        $data['cust_join_time'] = $input['customer_join_time'];
        $data['cust_end_time'] = $input['customer_end_time'];
        $data['cust_remark'] = $input['customer_remark'];

        //事物创建数据
        $result = DB::transaction(function () use($cust_id, $data, $custEnclo) {
            CustomerDb::insert($data);

            if($custEnclo){
                CustEncloDb::insert($custEnclo);
            }
            return true;
        });

        if($result){
            return redirectPageMsg('1', "添加成功", route('customer.addCustomer'));
        }else{
            return redirectPageMsg('-1', "添加失败", route('customer.addCustomer'));
        }
    }
    
    //编辑客户视图
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

        //客户附件
        $customer['custEnclo'] = CustEncloDb::where('cust_id', $id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->toArray();
        
        //获取合同下拉菜单信息
        $customer['select'] = SysAssDb::whereIn('ass_type', array('customer_type', 'contract_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('customer.editCustomer', $customer);
    }
    
    //更新客户信息
    public function updateCustomer()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'customer_type' => 'required|between:32,32',
            'customer_num' => 'required|between:1,200',
            'customer_name' => 'required|between:1,200',
            'customer_id' => 'required|between:32,32',
            'customer_phone' => 'max:18',
            'customer_fax' => 'max:40',
            'customer_address' => 'max:180',
            'customer_website' => 'max:180',
            'customer_tax_num' => 'max:28',
            'customer_join_time' => 'required|date',
            'customer_end_time' => 'required|date',
        ];
        $message = [
            'customer_type.required' => '请填写选择客户类别',
            'customer_type.between' => '客户类别参数错误',
            'customer_num.required' => '请填写客户编号',
            'customer_num.between' => '客户编号字符数超出范围',
            'customer_name.required' => '请填写客户名称',
            'customer_name.between' => '客户名称字符数超出范围',
            'customer_id.required' => '参数不存在',
            'customer_id.integer' => '参数错误',
            'customer_phone.max' => '联系电话字符数超出范围',
            'customer_fax.max' => '传真字符数超出范围',
            'customer_address.max' => '地址字符数超出范围',
            'customer_website.max' => '网站字符数超出范围',
            'customer_tax_num.max' => '税号字符数超出范围',
            'customer_join_time.required' => '请选择加入日期',
            'customer_join_time.date' => '加入时间格式错误',
            'customer_end_time.required' => '请选择撤出日期',
            'customer_end_time.date' => '撤出日期格式错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('customer.addCustomer'));
        }
        $cust_id = $input['customer_id'];
        //客户是否存在
        $result = CustomerDb::where('cust_id', $input['customer_id'])
                            ->first();
        if(!$result){
            return redirectPageMsg('-1', "修改失败，客户不存在", route('customer.index'));
        }
        //编号、名称是否存在
        $result = CustomerDb::where('cust_id','<>', $input['customer_id'])
            ->where(function ($query) use($input) {
                $query->where('cust_num', $input['customer_num'])
                    ->orWhere('cust_name', $input['customer_name']);
            })
            ->first();
        if($result){
            return redirectPageMsg('-1', "修改失败，客户编号或客户名称重复", route('customer.index'));
        }

        //客户附件
        $custEnclo = array();
        //移动单据文件
        if($input['enclosure']){
            $enclosures = explode('|', $input['enclosure']);
            foreach($enclosures as $k => $v){
                $fileName = explode(',', $v);
                if(count($fileName) != 2){
                    return redirectPageMsg('-1', '保存失败，附件名称格式化错误！', route('customer.editCustomer')."?id=".$input['customer_id']);
                }
                $directory = 'customer/'.session('userInfo.user_id').'/'.$fileName[1];
                $exists = Storage::disk('storageTemp')->exists($directory);
                if(!$exists){
                    return redirectPageMsg('-1', '保存失败，附件获取失败，请刷新后重试！', route('customer.editCustomer')."?id=".$input['customer_id']);
                }
                $oldFile = 'uploads/customer/'.session('userInfo.user_id').'/'.$fileName[1];
                $newFile = 'enclosure/customer/'.$cust_id.'/'.$fileName[1];
                $result = Storage::move($oldFile, $newFile);
                if(!$result){
                    return redirectPageMsg('-1', '保存失败，附件保存失败，请刷新后重试！', route('customer.editCustomer')."?id=".$input['customer_id']);
                }

                $custEnclo[$k]['enclo_id'] = getId();
                $custEnclo[$k]['cust_id'] = $cust_id;
                $custEnclo[$k]['enclo_name'] = $fileName[0];
                $custEnclo[$k]['enclo_url'] = $newFile;
                $custEnclo[$k]['created_at'] = date('Y-m-d H:i:s', time());
                $custEnclo[$k]['updated_at'] = date('Y-m-d H:i:s', time());
            }
        }

        //格式化数据
        $data['cust_type'] = $input['customer_type'];
        $data['cust_num'] = $input['customer_num'];
        $data['cust_name'] = $input['customer_name'];
        $data['cust_phone'] = $input['customer_phone'];
        $data['cust_fax'] = $input['customer_fax'];
        $data['cust_address'] = $input['customer_address'];
        $data['cust_website'] = $input['customer_website'];
        $data['cust_tax_num'] = $input['customer_tax_num'];
        $data['cust_join_time'] = $input['customer_join_time'];
        $data['cust_end_time'] = $input['customer_end_time'];
        $data['cust_remark'] = $input['customer_remark'];

        //事物创建数据
        $result = DB::transaction(function () use($data, $custEnclo, $cust_id) {
            CustomerDb::where('cust_id', $cust_id)
                ->update($data);
            if($custEnclo){
                CustEncloDb::insert($custEnclo);
            }
            return true;
        });

        if($result){
            return redirectPageMsg('1', "编辑成功", route('customer.listCustomer')."?id=".$input['customer_id']);
        }else{
            return redirectPageMsg('-1', "编辑失败", route('customer.editCustomer')."?id=".$input['customer_id']);
        }
    }

    //查看客户视图
    public function listCustomer()
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

        //获取客户信息
        $customer = CustomerDb::from('customer AS c')
            ->leftjoin('sys_assembly AS sa', 'ass_id', '=', 'c.cust_type')
            ->where('c.cust_id', $id)
            ->get()
            ->first();
        if(!$customer){
            return redirectPageMsg('-1', "客户不存在", route('customer.index'));
        }

        //客户附件
        $customer['custEnclo'] = CustEncloDb::where('cust_id', $id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->toArray();
       
        return view('customer.listCustomer', $customer);
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
        $filePath = 'uploads/customer/'.session('userInfo.user_id');
        $rel = $file->move($filePath ,$fileName);

        //$rel = Storage::disk('storageTemp')->put($filePath, file_get_contents($realPath));
        if(!$rel){
            echoAjaxJson('-1', '上传失败，请刷新后重试！');
        }
        $url = asset('uploads/customer/'.session('userInfo.user_id')).'/'.$fileName;
        $data['fUrl'] = $url;
        $data['url'] = $fileName;
        echoAjaxJson('1', '上传成功', $data);
    }

    //删除附件
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
        $enclo = CustEncloDb::where('enclo_id', $id)
            ->first();
        if(!$enclo){
            echoAjaxJson('-1', "删除失败，附件不存在！");
        }

        //删除附件
        $result = CustEncloDb::where('enclo_id', $id)
            ->delete();

        $custDir = substr($enclo->enclo_url ,10 ,strlen($enclo->enclo_url));
        Storage::disk('storage')->delete($custDir);

        if($result){
            echoAjaxJson('1', "删除成功");
        }else{
            echoAjaxJson('-1', "删除失败，请刷新后重试！");
        }
    }
}
