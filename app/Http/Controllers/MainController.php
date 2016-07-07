<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MainController extends Common\CommonController
{
    public function index()
    {
        return view('index');
    }
}
