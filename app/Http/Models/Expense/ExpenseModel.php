<?php

namespace App\Http\Models\Expense;

use Illuminate\Database\Eloquent\Model;

class ExpenseModel extends Model
{
    protected $table = 'expense';                           //表名称
    protected $primaryKey = 'expense_id';                   //主键
}
