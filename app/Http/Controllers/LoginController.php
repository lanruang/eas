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
        return view('login');
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
            'userName.email' => '用户名格式错误',
            'password.required' => '密码未填写',
        ];
        $validator = Validator::make($input, $rules, $message);
        //返回验证信息
        /*if($validator->fails()){
            return redirect('login')
                    ->withErrors($validator);
        }*/
        $userInfo = Login::where('email', $input['userName'])
                            ->first();
        //判断用户名
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
        $userInfo = $userInfo->toArray();
        unset($userInfo['password']);
        unset($userInfo['remember_token']);
        //存储用户数据
        session(['userInfo' => $userInfo]);
        return redirect('/');
    }

}
