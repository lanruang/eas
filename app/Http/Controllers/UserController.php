<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class UserController extends Common\Controller
{
    //
    public function index()
    {
        return view('user');
    }
}
