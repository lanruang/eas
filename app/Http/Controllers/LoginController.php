<?php

namespace App\Http\Controllers;


use App\Http\Models\Login AS LoginDb;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;
use App\Http\Models\PermissionModel AS permissionDb;

class LoginController extends Common\Controller
{
    //用户登录
    public function index()
    {
        return view('login.index');
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
        $userInfo = LoginDb::where('user_email', $input['userName'])
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
        //获取菜单、权限
        $menu = $this->getMenu();
        //更新登录时间
        LoginDb::where('user_id', $userInfo->user_id)
                ->update(['last_login' => date('Y-m-d H:i:s', time())]);
        $userInfo = $userInfo->toArray();
        unset($userInfo['password']);
        unset($userInfo['remember_token']);
        //存储用户数据
        session(['userInfo' => $userInfo]);
        //存储菜单、权限数据
        session(['userInfo.menu' => json_encode($menu['menu'])]);
        session(['userInfo.permission' => $menu['permission']]);
        return redirect('/');
    }

    //退出登录
    public function logout(Request $request)
    {
        $request->session()->forget('userInfo');
        return redirect()->route('login.index');
    }

    //获取权限
    private function getMenu(){
        //定义变量
        $arr['menu'] = array();
        $arr['permission'] = array();
        //获取权限
        $result = PermissionDb::select('id', 'pid', 'name', 'alias', 'icon')
                                ->orderBy('sort', 'asc')
                                ->get();
        //格式化菜单
        if($result){
            foreach ($result as $k => $v) {
                $arr['menu'][$k]['id'] = $v->id;
                $arr['menu'][$k]['pid'] = $v->pid;
                $arr['menu'][$k]['name'] = $v->name;
                $arr['menu'][$k]['url'] = $v->alias == "#" ? "#" : route($v->alias);
                $arr['menu'][$k]['alias'] = $v->alias;
                $arr['menu'][$k]['icon'] = $v->icon;
                //格式化权限
                if($v->url != "#") $arr['permission'][] = $v->url;
            }
        }

        return $arr;
    }
}
