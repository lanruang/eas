<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\UserModel AS UserDb;
use App\Http\Models\RoleModel AS RoleDb;
use Illuminate\Support\Facades\Input;
use Validator;

class CompanyController extends Common\CommonController
{
    //用户列表
    public function index()
    {
        return view('company.index');
    }

    //用户列表
    public function addCompany()
    {
        return view('company.addCompany');
    }

    //用户列表
    public function editCompany()
    {
        return view('company.editCompany');
    }
}
