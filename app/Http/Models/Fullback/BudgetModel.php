<?php

namespace App\Http\Models\Fullback;

use Illuminate\Database\Eloquent\Model;

class BudgetModel extends Model
{
    protected $connection = 'mysql_fullback';
    protected $table = 'ys';                            //表名称
    protected $primaryKey = 'ys';                   //主键
    public  $incrementing = false;
    
}
