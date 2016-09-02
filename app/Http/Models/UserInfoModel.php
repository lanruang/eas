<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfoModel extends Model
{
    protected $table = 'users_info';            //表名称
    protected $primaryKey = 'user_id';          //主键
}
