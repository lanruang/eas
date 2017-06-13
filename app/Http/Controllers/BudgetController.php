<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Route;

class BudgetController extends Common\CommonController
{
    public function index()
    {
        return view('budget.index');
    }

    public function addBudget()
    {
        return view('budget.addBudget');
    }
}
