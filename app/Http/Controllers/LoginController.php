<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\User\UserModel AS loginDb;
use App\Http\Models\Node\NodeModel AS nodeDb;
use App\Http\Models\SysConfig\SysConfigModel AS sysConfigDb;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;


class LoginController extends Common\CommonController
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
        if($validator->fails()) {
            return redirect(route('login.index'))
                ->withErrors($validator);
        }

        $userInfo = loginDb::leftjoin('users_base AS ub', 'users.user_id','=','ub.user_id')
                            ->leftjoin('department AS dep', 'ub.department','=','dep.dep_id')
                            ->leftjoin('positions AS pos', 'ub.positions','=','pos.pos_id')
                            ->select('users.*','dep.dep_name','dep.dep_id','pos.pos_name','pos.pos_id')
                            ->where('users.user_email', $input["userName"])
                            ->first();

        //判断用户
        if(empty($userInfo)){
            return redirect(route('login.index'))
                    ->withErrors(array('0'=>'邮箱不存在'));
        }
        //判断密码
        if($userInfo->password != md5($input['password']))
        {
            return redirect(route('login.index'))
                    ->withErrors(array('0'=>'密码错误'));
        }
        //是否允许登录
        if($userInfo->status == '0')
        {
            return redirect(route('login.index'))
                ->withErrors(array('0'=>'用户名已被禁止登录'));
        }

        //获取系统配置
        $sysConfig = $this->getSysConfig();
        if(!$sysConfig) {
            return redirect(route('login.index'))
                ->withErrors(array('0'=>'初始化参数错误，请联系管理员。'));
        }

        //获取菜单、权限
        $menu = $this->getMenu($userInfo->role_id, $userInfo->supper_admin);
        if(!$menu){
            return redirect(route('login.index'))
                ->withErrors(array('0'=>'没有登录权限，请联系管理员。'));
        }
        
        //更新登录时间
        loginDb::where('user_id', $userInfo->user_id)
                ->update(['last_login' => date('Y-m-d H:i:s', time())]);
        $userInfo = $userInfo->toArray();
        unset($userInfo['password']);
        unset($userInfo['remember_token']);
        //存储用户数据
        session(['userInfo' => $userInfo]);
        //存储菜单、权限数据
        session(['userInfo.menu' => json_encode($menu['menu'])]);
        session(['userInfo.permission' => $menu['permission']]);
        session(['userInfo.not_permission' => $menu['not_permission']]);
        //session(['userInfo.recycle' => $menu['recycle']]);
        session(['userInfo.sysConfig' => $sysConfig]);

        return redirect(route('main.index'));
    }

    //退出登录
    public function logout(Request $request)
    {
        $request->session()->forget('userInfo');
        return redirect()->route('login.index');
    }

    //获取权限
    private function getMenu($role_id, $sAdmin){
        //定义变量
        $arr['menu'] = array();
        $arr['permission'] = array();

        //是否超级管理员
        if($sAdmin){
            //获取数据
            $result = nodeDb::orderBy('sort', 'asc')
                ->get()
                ->toArray();
        }else{
            //获取菜单、权限
            $result = nodeDb::Join('role_node AS rn', 'rn.node_id', '=', 'node.id')
                ->where('node.status', '1')
                ->where('node.is_permission', '1')
                ->where('rn.role_id', $role_id)
                ->orderBy('node.sort', 'asc')
                ->get();
            if(!$result){
                return false;
            }
        }
        $arr['not_permission'] = array();
        //获取非权限类节点
        $res = nodeDb::where('status', 1)
            ->where('is_permission', 0)
            ->where('alias', '<>', '#')
            ->select('alias')
            ->get();
        foreach ($res as $v) {
            $arr['not_permission'][] = $v['alias'];
        }
        //格式化菜单
        if($result){
            foreach ($result as $k => $v) {
                $arr['menu'][$k]['id'] = $v['id'];
                $arr['menu'][$k]['pid'] = $v['pid'];
                $arr['menu'][$k]['name'] = $v['name'];
                $arr['menu'][$k]['url'] = $v['alias'] == "#" ? "#" : route($v['alias']);
                $arr['menu'][$k]['alias'] = $v['alias'];
                $arr['menu'][$k]['icon'] = $v['icon'];
                $arr['menu'][$k]['is_menu'] = $v['is_menu'];
                //格式化权限
                if($v['alias'] != "#") $arr['permission'][] = $v['alias'];
                //回收站权限配置
                if($v['is_recycle'] == 1){
                    $arr['recycle'][$k]['selectName'] = $v['recycle_name'];
                    $arr['recycle'][$k]['typeName'] = $v['recycle_type'];
                }
            }
        }

        return $arr;
    }

    //获取系统配置参数
    private function getSysConfig(){
        $sysConfig = sysConfigDb::get()
            ->toArray();
        if($sysConfig){
            foreach($sysConfig as $k => $v){
                $arr[$v['sys_class']][$v['sys_type']] = $v['sys_value'];
            }
        }else{
            $arr = false;
        }
        return $arr;
    }

}
