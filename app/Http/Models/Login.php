<?php

namespace App\Http\Models;

use App\CustomCollection;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $table = 'users';                 //表名称
    protected $primaryKey = 'user_id';          //主键
}
