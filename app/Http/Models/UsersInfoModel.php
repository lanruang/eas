<?php

namespace App\Http\Models;

use App\CustomCollection;
use Illuminate\Database\Eloquent\Model;

class UsersInfoModel extends Model
{
    protected $table = 'users_info';            //表名称
    protected $primaryKey = 'user_id';          //主键
}
