<?php

namespace App\Http\Models\Fullback;

use Illuminate\Database\Eloquent\Model;

class BudgetSubjectModel extends Model
{
    protected $connection = 'mysql_fullback';
    protected $table = 'yskm';        //表名称
}
