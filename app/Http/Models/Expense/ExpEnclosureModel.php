<?php

namespace App\Http\Models\Expense;

use Illuminate\Database\Eloquent\Model;

class ExpEnclosureModel extends Model
{
    protected $table = 'expense_enclosure';                 //表名称
    protected $primaryKey = 'enclosure_id';                 //主键
}
