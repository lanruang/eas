<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Models\PermissionModel AS permissionDb;
use Illuminate\Support\Facades\Route;

class MainController extends Common\Controller
{
    public function index()
    {
        return view('main.index');
    }
}
