<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class SysMsgController extends Common\Controller
{

    //信息提示
    public function sysMessage(Request $request, $status, $msg, $url)
    {
        //1-正常，0-提示，-1-错误
        $result['status'] = base64_decode($status);
        $result['msg'] = base64_decode($msg);
        $result['url'] = base64_decode($url);

        return view('layouts.PageMsg', $result);
    }
}
