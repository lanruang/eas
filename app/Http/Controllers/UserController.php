<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\UserModel AS User;
use App\Http\Requests;

class UserController extends Common\Controller
{
    public function index()
    {
        //获取用户信息
        $userInfo = User::getUserInfo(1);
        p($userInfo);
        return view('user');
    }
}
