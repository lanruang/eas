<?php

namespace App\Http\Models\System;

use Illuminate\Database\Eloquent\Model;

class SysAssemblyModel extends Model
{
    protected $table = 'sys_assembly';                      //表名称
    protected $primaryKey = 'ass_id';                   //主键
    public  $incrementing = false;
}
