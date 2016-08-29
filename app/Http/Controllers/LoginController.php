<?php

namespace App\Http\Controllers;


use App\Http\Models\Login;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;

class LoginController extends Common\Controller
{
    //用户登录
    public function login()
    {
        return view('login/login');
    }

    //验证登录
    public function checkLogin(Request $request)
    {
        //验证表单
        $input = Input::all();
        $rules = [
            'userName' => 'required|email',
            'password' => 'required',
        ];
        $message = [
            'userName.required' => '用户名未填写',
            'userName.email' => '邮箱格式错误',
            'password.required' => '密码未填写',
        ];
        $validator = Validator::make($input, $rules, $message);
        //返回验证信息
        if($validator->fails()){
            return redirect('login')
                    ->withErrors($validator);
        }
        $userInfo = Login::where('user_email', $input['userName'])
                            ->first();
        //判断用户
        if(empty($userInfo)){
            return redirect('login')
                    ->withErrors(array('0'=>'邮箱不存在'));
        }
        //判断密码
        if($userInfo->password != md5($input['password']))
        {
            return redirect('login')
                    ->withErrors(array('0'=>'密码错误'));
        }
        //更新登录时间
        Login::where('user_id', $userInfo->user_id)
                ->update(['last_login' => date('Y-m-d H:i:s', time())]);
        $userInfo = $userInfo->toArray();
        unset($userInfo['password']);
        unset($userInfo['remember_token']);
        //存储用户数据
        session(['userInfo' => $userInfo]);
        return redirect('/');
    }

    //退出登录
    public function logout(Request $request)
    {
        $request->session()->forget('userInfo');
        return redirect('/login');
    }

}
