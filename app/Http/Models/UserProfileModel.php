<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfileModel extends Model
{
    protected $table = 'users_profile';         //表名称
    protected $primaryKey = 'user_id';          //主键
}
