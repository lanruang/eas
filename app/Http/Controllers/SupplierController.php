<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\Supplier\SupplierModel AS SupplierDb;
use Illuminate\Support\Facades\Input;
use Validator;
use Storage;
use App\Http\Models\System\SysAssemblyModel AS SysAssDb;
use App\Http\Models\Supplier\SuppEnclosureModel AS SuppEncloDb;
use Illuminate\Support\Facades\DB;

class SupplierController extends Common\CommonController
{
    //供应商列表
    public function index()
    {
        return view('supplier.index');
    }

    //供应商列表
    public function getSupplier(Request $request){
        //验证传输方式
        if(!$request->ajax())
        {
            echoAjaxJson('-1', '非法请求');
        }
        //验证表单
        $input = Input::all();
        //分页
        $skip = isset($input['start']) ? intval($input['start']) : 0;//从多少开始
        $take = isset($input['length']) ? intval($input['length']) : 10;//数据长度

        //获取记录总数
        $total = SupplierDb::count();
        //获取数据
        $result = SupplierDb::select('supp_id AS id', 'supp_num', 'supp_name')
            ->orderBy('supp_status', 'DESC')
            ->orderBy('supp_name', 'ASC')
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

    //添加供应商视图
    public function addSupplier()
    {
        //获取下拉菜单信息
        $data['select'] = SysAssDb::whereIn('ass_type', array('supplier_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('supplier.addSupplier', $data);
    }

    //添加供应商
    public function createSupplier()
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'supplier_num' => 'required|between:1,200',
            'supplier_name' => 'required|between:1,200',
            'supplier_type' => 'required|between:32,32',
            'supplier_phone' => 'max:18',
            'supplier_fax' => 'max:40',
            'supplier_address' => 'max:180',
            'supplier_website' => 'max:180',
            'supplier_tax_num' => 'max:28',
            'supplier_join_time' => 'required|date',
            'supplier_end_time' => 'required|date',
        ];
        $message = [
            'supplier_num.required' => '请填写供应商编号',
            'supplier_num.between' => '供应商编号字符数超出范围',
            'supplier_name.required' => '请填写供应商名称',
            'supplier_name.between' => '供应商名称字符数超出范围',
            'supplier_type.required' => '请填写选择供应商类别',
            'supplier_type.between' => '供应商类别参数错误',
            'supplier_phone.max' => '联系电话字符数超出范围',
            'supplier_fax.max' => '传真字符数超出范围',
            'supplier_address.max' => '地址字符数超出范围',
            'supplier_website.max' => '网站字符数超出范围',
            'supplier_tax_num.max' => '税号字符数超出范围',
            'supplier_join_time.required' => '请选择加入日期',
            'supplier_join_time.date' => '加入时间格式错误',
            'supplier_end_time.required' => '请选择撤出日期',
            'supplier_end_time.date' => '撤出日期格式错误',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('supplier.addSupplier'));
        }

        //供应商是否存在
        $result = SupplierDb::where('supp_num', $input['supplier_num'])
            ->orWhere('supp_name', $input['supplier_name'])
            ->first();
        if($result){
            return redirectPageMsg('-1', "添加失败，供应商编号或供应商名称重复", route('supplier.addSupplier'));
        }

        $supp_id = getId();

        //客户附件
        $suppEnclo = array();
        //移动单据文件
        if($input['enclosure']){
            $enclosures = explode('|', $input['enclosure']);
            foreach($enclosures as $k => $v){
                $fileName = explode(',', $v);
                if(count($fileName) != 2){
                    return redirectPageMsg('-1', '保存失败，附件名称格式化错误！', route('supplier.addSupplier'));
                }
                $directory = 'supplier/'.session('userInfo.user_id').'/'.$fileName[1];
                $exists = Storage::disk('storageTemp')->exists($directory);
                if(!$exists){
                    $contractDir = 'supplier/'.$supp_id;
                    Storage::disk('storage')->deleteDirectory($contractDir);
                    return redirectPageMsg('-1', '保存失败，附件获取失败，请刷新后重试！', route('supplier.addSupplier'));
                }
                $oldFile = 'uploads/supplier/'.session('userInfo.user_id').'/'.$fileName[1];
                $newFile = 'enclosure/supplier/'.$supp_id.'/'.$fileName[1];
                $result = Storage::move($oldFile, $newFile);
                if(!$result){
                    $contractDir = 'supplier/'.$supp_id;
                    Storage::disk('storage')->deleteDirectory($contractDir);
                    return redirectPageMsg('-1', '保存失败，附件保存失败，请刷新后重试！', route('supplier.addSupplier'));
                }

                $suppEnclo[$k]['enclo_id'] = getId();
                $suppEnclo[$k]['supp_id'] = $supp_id;
                $suppEnclo[$k]['enclo_name'] = $fileName[0];
                $suppEnclo[$k]['enclo_url'] = $newFile;
                $suppEnclo[$k]['created_at'] = date('Y-m-d H:i:s', time());
                $suppEnclo[$k]['updated_at'] = date('Y-m-d H:i:s', time());
            }
        }

        //创建客户
        $data['supp_id'] = $supp_id;
        $data['supp_type'] = $input['supplier_type'];
        $data['supp_num'] = $input['supplier_num'];
        $data['supp_name'] = $input['supplier_name'];
        $data['supp_phone'] = $input['supplier_phone'];
        $data['supp_fax'] = $input['supplier_fax'];
        $data['supp_address'] = $input['supplier_address'];
        $data['supp_website'] = $input['supplier_website'];
        $data['supp_tax_num'] = $input['supplier_tax_num'];
        $data['supp_join_time'] = $input['supplier_join_time'];
        $data['supp_end_time'] = $input['supplier_end_time'];
        $data['supp_remark'] = $input['supplier_remark'];

        //事物创建数据
        $result = DB::transaction(function () use($supp_id, $data, $suppEnclo) {
            SupplierDb::insert($data);

            if($suppEnclo){
                SuppEncloDb::insert($suppEnclo);
            }
            return true;
        });

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
        $supplier = SupplierDb::where('supp_id', $id)
            ->get()
            ->first()
            ->toArray();
        if(!$supplier){
            return redirectPageMsg('-1', "参数错误", route('supplier.index'));
        }

        //供应商附件
        $supplier['suppEnclo'] = SuppEncloDb::where('supp_id', $id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->toArray();

        //获取下拉菜单信息
        $supplier['select'] = SysAssDb::whereIn('ass_type', array('supplier_type'))
            ->select('ass_type', 'ass_text', 'ass_value')
            ->orderBy('ass_sort')
            ->get()
            ->toArray();

        return view('supplier.editSupplier', $supplier);
    }
    
    //更新供应商信息
    public function updateSupplier()
    {
        //验证表单
        $input = Input::all();

        $rules = [
            'supplier_num' => 'required|between:1,200',
            'supplier_name' => 'required|between:1,200',
            'supplier_id' => 'required|between:32,32',
            'supplier_type' => 'required|between:32,32',
            'supplier_phone' => 'max:18',
            'supplier_fax' => 'max:40',
            'supplier_address' => 'max:180',
            'supplier_website' => 'max:180',
            'supplier_tax_num' => 'max:28',
            'supplier_join_time' => 'required|date',
            'supplier_end_time' => 'required|date',
        ];
        $message = [
            'supplier_num.required' => '请填写供应商编号',
            'supplier_num.between' => '供应商编号字符数超出范围',
            'supplier_name.required' => '请填写供应商名称',
            'supplier_name.between' => '供应商名称字符数超出范围',
            'supplier_id.required' => '参数不存在',
            'supplier_id.between' => '参数错误',
            'supplier_type.required' => '请填写选择供应商类别',
            'supplier_type.between' => '供应商类别参数错误',
            'supplier_phone.max' => '联系电话字符数超出范围',
            'supplier_fax.max' => '传真字符数超出范围',
            'supplier_address.max' => '地址字符数超出范围',
            'supplier_website.max' => '网站字符数超出范围',
            'supplier_tax_num.max' => '税号字符数超出范围',
            'supplier_join_time.required' => '请选择加入日期',
            'supplier_join_time.date' => '加入时间格式错误',
            'supplier_end_time.required' => '请选择撤出日期',
            'supplier_end_time.date' => '撤出日期格式错误',
        ];

        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            return redirectPageMsg('-1', $validator->errors()->first(), route('supplier.addSupplier'));
        }
        $supp_id = $input['supplier_id'];
        //供应商是否存在
        $result = SupplierDb::where('supp_id', $supp_id)
                            ->first();
        if(!$result){
            return redirectPageMsg('-1', "修改失败，供应商不存在", route('supplier.index'));
        }
        //编号、名称是否存在
        $result = SupplierDb::where('supp_id','<>', $supp_id)
            ->where(function ($query) use($input) {
                $query->where('supp_num', $input['supplier_num'])
                    ->orWhere('supp_name', $input['supplier_name']);
            })
            ->first();
        if($result){
            return redirectPageMsg('-1', "修改失败，供应商编号或供应商名称重复", route('supplier.index'));
        }

        //供应商附件
        $custEnclo = array();
        //移动单据文件
        if($input['enclosure']){
            $enclosures = explode('|', $input['enclosure']);
            foreach($enclosures as $k => $v){
                $fileName = explode(',', $v);
                if(count($fileName) != 2){
                    return redirectPageMsg('-1', '保存失败，附件名称格式化错误！', route('supplier.editSupplier')."?id=".$input['supplier_id']);
                }
                $directory = 'supplier/'.session('userInfo.user_id').'/'.$fileName[1];
                $exists = Storage::disk('storageTemp')->exists($directory);
                if(!$exists){
                    return redirectPageMsg('-1', '保存失败，附件获取失败，请刷新后重试！', route('supplier.editSupplier')."?id=".$input['supplier_id']);
                }
                $oldFile = 'uploads/supplier/'.session('userInfo.user_id').'/'.$fileName[1];
                $newFile = 'enclosure/supplier/'.$supp_id.'/'.$fileName[1];
                $result = Storage::move($oldFile, $newFile);
                if(!$result){
                    return redirectPageMsg('-1', '保存失败，附件保存失败，请刷新后重试！', route('supplier.editSupplier')."?id=".$input['supplier_id']);
                }

                $suppEnclo[$k]['enclo_id'] = getId();
                $suppEnclo[$k]['supp_id'] = $supp_id;
                $suppEnclo[$k]['enclo_name'] = $fileName[0];
                $suppEnclo[$k]['enclo_url'] = $newFile;
                $suppEnclo[$k]['created_at'] = date('Y-m-d H:i:s', time());
                $suppEnclo[$k]['updated_at'] = date('Y-m-d H:i:s', time());
            }
        }

        //格式化数据
        $data['supp_type'] = $input['supplier_type'];
        $data['supp_num'] = $input['supplier_num'];
        $data['supp_name'] = $input['supplier_name'];
        $data['supp_phone'] = $input['supplier_phone'];
        $data['supp_fax'] = $input['supplier_fax'];
        $data['supp_address'] = $input['supplier_address'];
        $data['supp_website'] = $input['supplier_website'];
        $data['supp_tax_num'] = $input['supplier_tax_num'];
        $data['supp_join_time'] = $input['supplier_join_time'];
        $data['supp_end_time'] = $input['supplier_end_time'];
        $data['supp_remark'] = $input['supplier_remark'];

        //事物创建数据
        $result = DB::transaction(function () use($data, $suppEnclo, $supp_id) {
            SupplierDb::where('supp_id', $supp_id)
                ->update($data);
            if($suppEnclo){
                SuppEncloDb::insert($suppEnclo);
            }
            return true;
        });

        if($result){
            return redirectPageMsg('1', "编辑成功", route('supplier.listSupplier')."?id=".$input['supp_id']);
        }else{
            return redirectPageMsg('-1', "编辑失败", route('supplier.editSupplier')."?id=".$input['supp_id']);
        }
    }

    //查看供应商视图
    public function listSupplier()
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
            return redirectPageMsg('-1', $validator->errors()->first(), route('suplier.index'));
        }
        $id = $input['id'];

        //获取客户信息
        $supplier = SupplierDb::from('supplier AS c')
            ->leftjoin('sys_assembly AS sa', 'ass_id', '=', 'c.supp_type')
            ->where('c.supp_id', $id)
            ->get()
            ->first();
        if(!$supplier){
            return redirectPageMsg('-1', "供应商不存在", route('supplier.index'));
        }

        //供应商附件
        $supplier['suppEnclo'] = SuppEncloDb::where('supp_id', $id)
            ->orderBy('created_at', 'ASC')
            ->get()
            ->toArray();

        return view('supplier.listSupplier', $supplier);
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
        $filePath = 'uploads/supplier/'.session('userInfo.user_id');
        $rel = $file->move($filePath ,$fileName);

        //$rel = Storage::disk('storageTemp')->put($filePath, file_get_contents($realPath));
        if(!$rel){
            echoAjaxJson('-1', '上传失败，请刷新后重试！');
        }
        $url = asset('uploads/supplier/'.session('userInfo.user_id')).'/'.$fileName;
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
        $enclo = SuppEncloDb::where('enclo_id', $id)
            ->first();
        if(!$enclo){
            echoAjaxJson('-1', "删除失败，附件不存在！");
        }

        //删除附件
        $result = SuppEncloDb::where('enclo_id', $id)
            ->delete();

        $suppDir = substr($enclo->enclo_url ,10 ,strlen($enclo->enclo_url));
        Storage::disk('storage')->delete($suppDir);

        if($result){
            echoAjaxJson('1', "删除成功");
        }else{
            echoAjaxJson('-1', "删除失败，请刷新后重试！");
        }
    }
}
