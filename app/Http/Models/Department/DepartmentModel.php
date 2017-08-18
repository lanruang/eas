<?php

namespace App\Http\Models\Department;

use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    protected $table = 'department';                      //表名称
    protected $primaryKey = 'dep_id';                   //主键
    public  $incrementing = false;
}
