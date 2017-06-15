<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetModel extends Model
{
    protected $table = 'budget';                            //表名称
    protected $primaryKey = 'budget_id';                   //主键
}
