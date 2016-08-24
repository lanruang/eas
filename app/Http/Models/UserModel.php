<?php

namespace App\Http\Models;

use App\CustomCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserModel extends Model
{
    protected $table = 'users';                 //表名称
    protected $primaryKey = 'user_id';          //主键
    
    //根据用户ID获取信息
    protected function getUserInfo($user_id)
    {
        $userInfo = DB::
        return $user_id;
    }
}
