<?php

namespace App\Http\Models\Permission;

use Illuminate\Database\Eloquent\Model;

class PermissionModel extends Model
{
    protected $table = 'permission';                        //表名称
    protected $primaryKey = 'id';                           //主键
    public  $incrementing = false;
}
