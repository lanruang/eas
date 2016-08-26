<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\User AS User;
use App\Http\Models\UserProfileModel AS UserProfile;
use App\Http\Models\UserInfoModel AS UserInfo;
use App\Http\Requests;

class UserController extends Common\Controller
{
    public function index(Request $request)
    {
        //获取用户信息
        $userProfile = UserProfile::where('user_id',$request->session()->get('userInfo.user_id'))
                                        ->first();
        $userInfo = UserInfo::where('user_id',$request->session()->get('userInfo.user_id'))
                                ->first();
        return view('user', ['userProfile' => $userProfile, 'userInfo' => $userInfo]);
    }
    
    //修改密码
    public function editPwd()
    {
        echo 11;
    }
}
