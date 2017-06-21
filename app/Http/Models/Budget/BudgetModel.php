<?php

namespace App\Http\Models\Budget;

use Illuminate\Database\Eloquent\Model;

class BudgetModel extends Model
{
    protected $table = 'budget';                            //表名称
    protected $primaryKey = 'budget_id';                   //主键
    
}
