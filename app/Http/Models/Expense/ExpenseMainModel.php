<?php

namespace App\Http\Models\Expense;

use Illuminate\Database\Eloquent\Model;

class ExpenseMainModel extends Model
{
    protected $table = 'expense_main';                           //表名称
    protected $primaryKey = 'exp_id';                   //主键
    public  $incrementing = false;
}
