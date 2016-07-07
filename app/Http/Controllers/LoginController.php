<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class LoginController extends Common\CommonController
{
    public function login()
    {
        return view('login');
    }
}
