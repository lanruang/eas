<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\UserModel AS User;
use App\Http\Models\UserProfileModel AS UserProfile;
use App\Http\Models\UserInfoModel AS UserInfo;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;

class UserController extends Common\Controller
{
    public function index(Request $request)
    {
        //获取用户信息
        $userProfile = UserProfile::where('user_id',$request->session()->get('userInfo.user_id'))
                                        ->first();
        $userInfo = UserInfo::where('user_id',$request->session()->get('userInfo.user_id'))
                                ->first();
        return view('user.index', ['userProfile' => $userProfile, 'userInfo' => $userInfo]);
    }
    
    //修改密码
    public function editPwd(Request $request)
    {
        //验证传输方式
       if(!$request->ajax())
        {
            echoAjaxJson(0, '非法请求');
        }
        //验证表单
        $input = Input::all();
        $rules = [
            'oldPassword' => 'required',
            'password' => 'required|confirmed'
        ];
        $message = [
            'oldPassword.required' => '密码未填写',
            'password.required' => '新密码未填写',
            'password.confirmed' => '2次密码不相同',
        ];
        $validator = Validator::make($input, $rules, $message);
        if($validator->fails()){
            echoAjaxJson(0, $validator->errors()->first());
        }
        //验证原密码
        $userInfo = User::where('user_id', $request->session()->get('userInfo.user_id'))
                            ->where('password', md5($input['oldPassword']))
                            ->first();
        if(empty($userInfo)){
            echoAjaxJson(0, '原密码错误');
        }
        //更新密码
        $result = User::where('user_id', $request->session()->get('userInfo.user_id'))
                            ->update(['password' => md5($input['password'])]);
        if($result)
        {
            echoAjaxJson(1, '修改成功，请重新登录。');
        }
        echoAjaxJson(0, '修改失败');
    }
}
