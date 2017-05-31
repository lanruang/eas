<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;

class RecycleController extends Common\CommonController
{
    public function index()
    {
        p(session('userInfo.recycle'));
        return view('recycle.index');
    }
}
