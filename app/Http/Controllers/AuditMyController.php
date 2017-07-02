<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;

class AuditMyController extends Common\CommonController
{
    public function index()
    {
        return view('auditMy.index');
    }
}
