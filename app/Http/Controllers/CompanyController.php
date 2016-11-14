<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\CompanyModel AS companyModelDb;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Input;

class CompanyController extends Common\Controller
{
    public function index()
    {
        //获取公司信息
        $result = companyModelDb::where('id', '1')
            ->first()
            ->toArray();

        return view('company.index', $result);
    }

    //编辑权限视图
    public function editCompany(Request $request)
    {
        //获取公司信息
        $result = companyModelDb::where('id', '1')
            ->first()
            ->toArray();

        return view('company.editCompany', $result);
    }

    //更新角色
    public function updateCompany()
    {
        //验证表单
        $input = Input::all();
        //检测id类型是否整数
        if(!array_key_exists('company_id', $input)){
            redirectPageMsg('-1', '参数错误', route('company.index'));
        };
        $rules = [
            "company_type" => "max:80",
            "company_address" => "max:80",
            "company_legal_person" => "max:25",
            "company_reg_capital" => "max:80",
            "company_reg_date" => "max:10",
            "company_operate_date" => "max:25",
            "company_credentials_number" => "max:25",
            "company_website_address" => "max:80",
            "company_phone" => "max:18",
            "company_fax" => "max:18"
        ];
        $message = [
            "company_type.max" => "类型字符数过多",
            "company_address.max" => "住所字符数过多",
            "company_legal_person.max" => "法定代表人字符数过多",
            "company_reg_capital.max" => "注册资本字符数过多",
            "company_reg_date.max" => "成立日期字符数过多",
            "company_operate_date.max" => "营业期限字符数过多",
            "company_credentials_number.max" => "统一社会信用代码字符数过多",
            "company_website_address.max" => "网站字符数过多",
            "company_phone.max" => "电话字符数过多",
            "company_fax.max" => "传真字符数过多"
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            redirectPageMsg('-1', $validator->errors()->first(), route('role.editRole')."/".$input['role_id']);
        }

        //格式化数据
        $data['type'] = $input['company_type'];
        $data['address'] = $input['company_address'];
        $data['legal_person'] = $input['company_legal_person'];
        $data['reg_capital'] = $input['company_reg_capital'];
        $data['reg_date'] = $input['company_reg_date'];
        $data['operate_date'] = $input['company_operate_date'];
        $data['business_operate'] = $input['company_business_operate'];
        $data['credentials_number'] = $input['company_credentials_number'];
        $data['website_address'] = $input['company_website_address'];
        $data['phone'] = $input['company_phone'];
        $data['fax'] = $input['company_fax'];
        //更新角色信息
        $result = companyModelDb::where('id', $input['company_id'])->update($data);
        if($result){
            redirectPageMsg('1', "编辑成功", route('company.index'));
        }else{
            redirectPageMsg('-1', "编辑失败", route('company.editCompany')."/".$input['company_id']);
        }
    }

}
