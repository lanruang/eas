<?php

namespace App\Http\Models\Role;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    protected $table = 'role';               //表名称
    protected $primaryKey = 'id';                   //主键
    public  $incrementing = false;
}
