<?php

namespace App\Http\Models\Budget;

use Illuminate\Database\Eloquent\Model;

class BudgetSubjectModel extends Model
{
    protected $table = 'budget_subject AS BudgetS';        //表名称
    protected $primaryKey = 'budget_id';                   //主键
}
