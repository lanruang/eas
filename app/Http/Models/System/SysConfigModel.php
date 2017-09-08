<?php

namespace App\Http\Models\System;

use Illuminate\Database\Eloquent\Model;

class SysConfigModel extends Model
{
    protected $table = 'sys_config';                      //表名称
    protected $primaryKey = 'sys_id';                   //主键
    public  $incrementing = false;
}
