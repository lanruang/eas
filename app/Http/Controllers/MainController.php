<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;


class MainController extends Common\Controller
{
    public function index()
    {
        return view('main.index');
    }
}
