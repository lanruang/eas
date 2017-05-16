<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    protected $table = 'department';                      //表名称
    protected $primaryKey = 'dep_id';                   //主键
}
