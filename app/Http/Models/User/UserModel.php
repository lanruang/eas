<?php

namespace App\Http\Models\User;

use App\CustomCollection;
use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'users';                 //表名称
    protected $primaryKey = 'user_id';          //主键
    public  $incrementing = false;
}
